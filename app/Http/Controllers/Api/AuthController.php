<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate( [
        'username' => 'required|string|max:255|unique:users,username',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'region' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ],
    [
        'username.unique' => 'Toto uživatelské jméno je již zabrané.',
        'email.unique' => 'Tento email je již použitý.',
        'password.min' => 'Heslo musí mít alespoň 8 znaků.',
        'password.confirmed' => 'Hesla se neshodují.',
    ]);

        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'region' => $validated['region'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
        ]);

        return response()->json(['message' => 'Registrace proběhla úspěšně', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Neplatná data.',
                'errors' => $e->errors(),
            ], 422);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Neplatné přihlašovací údaje.',
                'errors' => [
                    'email' => ['Zadaný e-mail nebo heslo není správně.']
                ],
            ], 422);
        }

        $request->session()->regenerate();

        return response()->json(['message' => 'Přihlášení úspěšné.'], 200);
    }


    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $user = auth()->user();

        $path = $request->file('photo')->store('profile_photos', 'public');

        $user->avatar_path = 'storage/' . $path;
        $user->save();

        return response()->json([
            'message' => 'Profilový obrázek uložen',
            'avatar_path' => $user->avatar_path,
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Odhlášení proběhlo úspěšně']);
    }

}

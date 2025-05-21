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
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'region' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Špatné přihlašovací údaje'], 401);
        }

        $request->session()->regenerate();

        return response()->json(['message' => 'Přihlášení úspěšné']);
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

    /*public function logout(Request $request)
    {
        Auth::guard('web')->logout(); // odhlásí uživatele
        $request->session()->invalidate(); // zneplatní session
        $request->session()->regenerateToken(); // nový CSRF token

        return response()->json(['message' => 'Odhlášení úspěšné']);
    }*/

    public function logout(Request $request)
    {
        // Pokud používáš session-based auth
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Pokud používáš tokeny (sanctum personal access tokens), tak můžeš také smazat token
        // auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Odhlášení proběhlo úspěšně']);
    }

}

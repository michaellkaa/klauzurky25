<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    // Get all users sorted by ID
    public function index()
{
    $users = User::orderBy('id')->get();
    return response()->json($users);
}


    // Delete user by ID
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Uživatel nenalezen'], 404);
        }

        // Optional: prevent deleting the currently logged-in admin or yourself
        // if ($user->id === auth()->id()) {
        //     return response()->json(['message' => 'Nemůžeš smazat sám sebe'], 403);
        // }

        $user->delete();

        return response()->json(['message' => 'Uživatel smazán']);
    }
}

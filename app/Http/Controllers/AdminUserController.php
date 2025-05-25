<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id')->get();
        return response()->json($users);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Uživatel nenalezen'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Uživatel smazán']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Faculty;
use App\Models\University;

class FavoriteController extends Controller
{
     public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:university,faculty',
            'id' => 'required|integer',
        ]);

        $model = $request->type === 'university' ? University::class : Faculty::class;
        $favoritable = $model::findOrFail($request->id);

        // Check if already favorited to avoid duplicates (unique constraint should help too)
        $exists = Favorite::where('user_id', $request->user()->id)
            ->where('favoritable_type', $model)
            ->where('favoritable_id', $favoritable->id)
            ->exists();

        if (!$exists) {
            Favorite::create([
                'user_id' => $request->user()->id,
                'favoritable_type' => $model,
                'favoritable_id' => $favoritable->id,
            ]);
        }

        return response()->json(['message' => 'Favorited']);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'type' => 'required|in:university,faculty',
            'id' => 'required|integer',
        ]);

        $model = $request->type === 'university' ? University::class : Faculty::class;

        $favorite = Favorite::where('user_id', $request->user()->id)
            ->where('favoritable_type', $model)
            ->where('favoritable_id', $request->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Unfavorited']);
        }

        return response()->json(['message' => 'Favorite not found'], 404);
    }

    /*public function isFavorited($facultyId)
    {
        $userId = auth()->id(); // Získáme ID přihlášeného uživatele

        // Zkontrolujeme, zda existuje záznam pro tuto fakultu a uživatele
        $isFavorited = Favorite::where('user_id', $userId)
                                ->where('faculty_id', $facultyId)
                                ->exists();

        return response()->json(['isFavorited' => $isFavorited]);
    }

    public function addFavorite($facultyId)
    {
        $userId = auth()->id(); // Získáme ID přihlášeného uživatele

        // Přidáme do databáze
        Favorite::create([
            'user_id' => $userId,
            'faculty_id' => $facultyId,
        ]);

        return response()->json(['message' => 'Added to favorites']);
    }

    public function removeFavorite($facultyId)
    {
        $userId = auth()->id(); // Získáme ID přihlášeného uživatele

        // Odstraníme z databáze
        Favorite::where('user_id', $userId)
                ->where('faculty_id', $facultyId)
                ->delete();

        return response()->json(['message' => 'Removed from favorites']);
    }*/


}

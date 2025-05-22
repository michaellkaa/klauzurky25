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
    }

    public function check(Request $request)
    {
        $request->validate([
            'type' => 'required|in:university,faculty',
            'id' => 'required|integer',
        ]);

        $model = $request->type === 'university' ? University::class : Faculty::class;

        $isFavorited = Favorite::where('user_id', $request->user()->id)
            ->where('favoritable_type', $model)
            ->where('favoritable_id', $request->id)
            ->exists();

        return response()->json(['favorited' => $isFavorited]);
    }


    public function isFavorited(Request $request)
    {
        $user = Auth::user();
        $id = $request->id;
        $type = $request->type;

        if (!$user) {
            return response()->json(['favorited' => false]);
        }

        // Map 'faculty' or 'university' to model class
        $modelClass = match ($type) {
            'faculty' => \App\Models\Faculty::class,
            'university' => \App\Models\University::class,
            default => null,
        };

        if (!$modelClass) {
            return response()->json(['favorited' => false]);
        }

        $item = $modelClass::find($id);
        if (!$item) {
            return response()->json(['favorited' => false]);
        }

        $favorited = $item->favoritedByUsers()->where('user_id', $user->id)->exists();

        return response()->json(['favorited' => $favorited]);
    }

    public function favorite(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $id = $request->id;
        $type = $request->type;

        $modelClass = match ($type) {
            'faculty' => \App\Models\Faculty::class,
            'university' => \App\Models\University::class,
            default => null,
        };

        if (!$modelClass) {
            return response()->json(['message' => 'Invalid type'], 400);
        }

        $item = $modelClass::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Attach favorite if not already favorited
        if (!$item->favoritedByUsers()->where('user_id', $user->id)->exists()) {
            $item->favoritedByUsers()->attach($user->id);
        }

        return response()->json(['message' => 'Favorited']);
    }

    public function unfavorite(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $id = $request->id;
        $type = $request->type;

        $modelClass = match ($type) {
            'faculty' => \App\Models\Faculty::class,
            'university' => \App\Models\University::class,
            default => null,
        };

        if (!$modelClass) {
            return response()->json(['message' => 'Invalid type'], 400);
        }

        $item = $modelClass::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Detach favorite if exists
        $item->favoritedByUsers()->detach($user->id);

        return response()->json(['message' => 'Unfavorited']);
    }

    public function userFavoriteFaculties(Request $request)
    {
        $user = $request->user();
        $faculties = $user->favoriteFaculties()->with('university')->get(); // nebo bez `with` pokud nechceš eager-load

        return response()->json($faculties);
    }

}

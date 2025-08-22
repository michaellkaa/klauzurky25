<?php

namespace App\Http\Controllers;

use App\Http\Resources\UniversityResource;
use App\Http\Resources\FacultyResource;
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

    //tady oblibene?
    public function isFavorited(Request $request)
    {
        $user = Auth::user();
        $id = $request->id;
        $type = $request->type;

        if (!$user) {
            return response()->json(['favorited' => false]);
        }

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
    //tady do oblibenych
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

        if (!$item->favoritedByUsers()->where('user_id', $user->id)->exists()) {
            $item->favoritedByUsers()->attach($user->id);
        }

        return response()->json(['message' => 'Favorited']);
    }
    //tady z oblibenych
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

        $item->favoritedByUsers()->detach($user->id);

        return response()->json(['message' => 'Unfavorited']);
    }
    //tady oblibene
    public function userFavoriteFaculties(Request $request)
    {
        /*$user = $request->user();
        $faculties = $user->favoriteFaculties()->get();

        return response()->json($faculties);*/
        $user = $request->user();
        $faculties = $user->favoriteFaculties()->get(); 

        return response()->json(FacultyResource::collection($faculties));
    }

    public function userFavoriteUniversities(Request $request)
    {
        $user = $request->user();
        $universities = $user->favoriteUniversities()->get(); 

        return response()->json(UniversityResource::collection($universities));
    }


}

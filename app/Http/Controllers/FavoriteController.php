<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Faculty;
use App\Models\University;

class FavoriteController extends Controller
{
    /*public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|in:faculty,university',
            'id' => 'required|integer',
        ]);

        $user = Auth::user();

        $type = $request->type === 'faculty' ? Faculty::class : University::class;
        $favorite = $user->favorites()
            ->where('favoritable_id', $request->id)
            ->where('favoritable_type', $type)
            ->first();

        if ($favorite) {
            // Already liked, so remove
            $favorite->delete();
            return response()->json(['liked' => false]);
        } else {
            // Not yet liked, so add
            $user->favorites()->create([
                'favoritable_id' => $request->id,
                'favoritable_type' => $type,
            ]);
            return response()->json(['liked' => true]);
        }
    }

    public function check(Request $request)
    {
        $user = $request->user();

        $exists = $user->favorites()
            ->where('favoritable_id', $request->id)
            ->where('favoritable_type', $request->type === 'university' ? \App\Models\University::class : \App\Models\Faculty::class)
            ->exists();

        return response()->json(['liked' => $exists]);
    }*/

}

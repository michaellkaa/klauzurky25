<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\University;
use App\Models\Faculty;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'universities' => [],
                'faculties' => [],
            ]);
        }

        $universities = University::where('name', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->get();

        $faculties = Faculty::where('name', 'like', "%{$query}%")
            ->orWhere('address', 'like', "%{$query}%")
            ->get();

        return response()->json([
            'universities' => $universities,
            'faculties' => $faculties,
        ]);
    }
}

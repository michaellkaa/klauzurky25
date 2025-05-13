<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\University;

class UniversityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'location' => 'nullable|string',
            'website' => 'nullable|string',
            'field' => 'nullable|string',
            'language' => 'nullable|string',
            'type' => 'nullable|string',
        ]);

        $university = University::create($validated);

        return response()->json($university, 201);
    }

    public function index()
    {
        return response()->json(University::all());
    }


    public function show($id)
    {
        return response()->json(University::findOrFail($id));
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faculty;

class FacultyController extends Controller
{
    public function index()
    {
        return response()->json(Faculty::all());
    }

    public function show($id)
    {
        return response()->json(Faculty::findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'university' => 'required|string|exists:universities,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'website' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'application_link' => 'nullable|string',
            'admission_notes' => 'nullable|string',
            'open_day_dates' => 'nullable|string',
            'open_day_url' => 'nullable|string',
            'exam_dates' => 'nullable|string',
            'application_fee' => 'nullable|string',
            'application_deadlines' => 'nullable|string',
            'bc_programs' => 'nullable|string',
            'mgr_programs' => 'nullable|string',
            'dr_programs' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'banner_url' => 'nullable|url',
            'facebook_url' => 'nullable|string',
            'instagram_url' => 'nullable|string',
            'twitter_url' => 'nullable|string',
            'fields_of_study' => 'nullable|string',
        ]);

        $faculty = Faculty::create($validated);
        return response()->json($faculty, 201);
    }

    public function destroy($id)
    {
        $faculty = Faculty::findOrFail($id);
        $faculty->delete();

        return response()->json(['message' => 'Faculty deleted']);
    }

    public function zamereni()
{
    $zamereni = Faculty::pluck('fields_of_study') // vezme všechny hodnoty z sloupce
        ->flatMap(function ($item) {
            return array_map('trim', explode(',', $item)); // rozdělí hodnoty podle čárek
        })
        ->filter() // odstraní null / prázdné
        ->unique()
        ->values();

    return response()->json($zamereni);
}

}

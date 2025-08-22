<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\Field;

use App\Http\Resources\FacultyResource;


class FacultyController extends Controller
{
/*public function index(Request $request){
    
        $query = Faculty::query();

        if ($request->has('field')) {
            $field = $request->get('field');

            $query->whereJsonContains('fields_of_study', $field);
        }

        return response()->json($query->get());
    }*/

    public function index(Request $request)
{
    $query = Faculty::query();

    $query->with('favoritedByUsers');

    if ($request->has('field')) {
        $field = $request->get('field');
        $query->whereJsonContains('fields_of_study', $field);
    }

    $faculties = $query->get();

    return response()->json(FacultyResource::collection($faculties));
}

    public function show($id)
    {
        return response()->json(FacultyResource::make(Faculty::findOrFail($id)));
    }

    //tady data, ale data i v resource (optimaliace)
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
        $zamereni = Faculty::pluck('fields_of_study')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->filter()
            ->unique()
            ->values();

        return response()->json($zamereni);
    }

    public function getByField(Request $request)
    {
        $field = $request->query('field');

        if (!$field) {
            return response()->json(['error' => 'Missing field parameter'], 400);
        }

        $faculties = Faculty::where('fields_of_study', 'LIKE', "%$field%")->get();

        return response()->json($faculties);
    }

    //cool, doporucujeme fakulty a funguje to (ja doufam ze aspon moje komentare dokazuji ze nejsem ai)
    public function recommendedFaculties(Request $request)
    {
        $user = $request->user();
        $fieldNames = $user->recommendedFields->pluck('name');

        if ($fieldNames->isEmpty()) {
            return response()->json([]); 
        }
        
        $faculties = Faculty::query()
            ->with('favoritedByUsers')
            ->where(function ($query) use ($fieldNames) {
                foreach ($fieldNames as $name) {
                    $query->orWhere('fields_of_study', 'LIKE', '%' . $name . '%');
                }
            })
            ->get();

        return response()->json(FacultyResource::collection($faculties));
    }

}

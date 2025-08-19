<?php

namespace App\Http\Controllers;

use App\Models\Field;

class FieldController extends Controller
{
    public function index()
    {
        return response()->json(Field::select('id', 'name')->get());
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Field;
use App\Models\User;


class QuizController extends Controller
{
    public function getQuestions()
    {
        return Question::with('answers')->get();
    }

    //tady pocitam vysledky
    public function calculateResult(Request $request)
    {
        $answers = $request->input('answers'); 
        $scores = [];

        foreach ($answers as $questionId => $answerId) {
            $answer = Answer::with('fields')->find($answerId);
            if ($answer) {
                foreach ($answer->fields as $field) {
                    $scores[$field->id] = ($scores[$field->id] ?? 0) + $field->pivot->points;
                }
            }
        }
        
        //the struggle was real
        if (empty($scores)) {
            return response()->json(['error' => 'Nepodařilo se spočítat skóre', 'answers' => $answers]);
        }

        $resultScores = [];
        foreach ($scores as $fieldId => $score) {
            $field = Field::find($fieldId);
            if ($field) {
                $resultScores[$field->name] = $score;
            }
        }
        //tady nechci aby to doporucovalo ostatni (ty vysledky nejsou tak presne potom, jelikoz hodne otazek tam ma i "ostatni")
        $filteredScores = array_filter($resultScores, function($name) {
            return stripos($name, 'ostatní') === false;
        }, ARRAY_FILTER_USE_KEY);

        $finalScores = !empty($filteredScores) ? $filteredScores : $resultScores;

        arsort($finalScores);
        $maxScore = reset($finalScores);
        $bestFields = [];

        foreach ($finalScores as $name => $score) {
            if ($score === $maxScore) {
                $bestFields[] = $name;
            } else {
                break;
            }
        }
        //v quiz page jsem nechala to jak to vyhazuje vysledky kdyby nahodou 
        return response()->json([
            'scores' => $resultScores,
            'recommended_fields' => $bestFields,
        ]);
    }

    public function storeResult(Request $request)
    {
        $user = $request->user();
        $fieldNames = $request->input('recommended_fields');  

        //tady id, id potrebujeme do tabulky
        $fieldIds = Field::whereIn('name', $fieldNames)->pluck('id')->toArray();

        //tady ukladam do tabulky
        $user->recommendedFields()->sync($fieldIds);

        return response()->json([
            'success' => true,
            'recommended_fields' => $fieldNames
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Field;

class QuizController extends Controller
{
    public function getQuestions()
    {
        return Question::with('answers')->get();
    }

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

        if (empty($scores)) {
            return response()->json(['error' => 'Nepodařilo se spočítat skóre', 'answers' => $answers]);
        }

        // nahrajeme názvy oborů
        $resultScores = [];
        foreach ($scores as $fieldId => $score) {
            $field = Field::find($fieldId);
            if ($field) {
                $resultScores[$field->name] = $score;
            }
        }

        // vyřadíme "ostatní ..." z doporučení
        $filteredScores = array_filter($resultScores, function($name) {
            return stripos($name, 'ostatní') === false;
        }, ARRAY_FILTER_USE_KEY);

        // když po odfiltrování nic nezbyde, použijeme původní
        $finalScores = !empty($filteredScores) ? $filteredScores : $resultScores;

        // seřadíme podle bodů
        arsort($finalScores);
        $bestFieldName = array_key_first($finalScores);

        return response()->json([
            'scores' => $resultScores,     // ukážeš celé body, včetně ostatních
            'recommended' => $bestFieldName, // doporučení už jen bez "ostatních"
        ]);
    }

}

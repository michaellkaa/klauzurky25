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
        $maxScores = []; // pro normalizaci

        foreach ($answers as $questionId => $answerId) {
            $answer = Answer::with('fields')->find($answerId);
            if ($answer) {
                foreach ($answer->fields as $field) {
                    $scores[$field->id] = ($scores[$field->id] ?? 0) + $field->pivot->points;
                    $maxScores[$field->id] = ($maxScores[$field->id] ?? 0) + $field->pivot->points; // kumulace max bodů
                }
            }
        }

        if (empty($scores)) {
            return response()->json(['error' => 'Nepodařilo se spočítat skóre', 'answers' => $answers]);
        }

        // normalizace na procenta
        $relativeScores = [];
        foreach ($scores as $fieldId => $score) {
            $relativeScores[$fieldId] = $maxScores[$fieldId] > 0 ? $score / $maxScores[$fieldId] : 0;
        }

        arsort($relativeScores);
        $bestFieldId = array_key_first($relativeScores);
        $bestField = Field::find($bestFieldId);

        // pokud žádný field nemá aspoň 50% skóre, doporučíme "ostatní"
        if (($relativeScores[$bestFieldId] ?? 0) < 0.5) {
            $bestField = Field::where('name', 'like', 'ostatní%')->first();
        }

        return response()->json([
            'scores' => $relativeScores,
            'recommended' => $bestField ? $bestField->name : null,
        ]);
    }


}

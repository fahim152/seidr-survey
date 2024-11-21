<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Response;

class QuestionController extends Controller
{
    /**
     * Store the survey responses.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'answers' => 'required|array', // Ensure answers is an array
            'question_id' => 'required|exists:questions,id', // Ensure question_id is valid
        ]);

        Response::create([
            'answers' => json_encode($validated['answers']),
            'question_id' => $validated['question_id'],
        ]);

        return response()->json(['message' => 'Survey submitted successfully'], 201);
    }

    /**
     * Fetch questions from the database.
     */
    public function fetchQuestions()
    {
        $questionSet = Question::orderBy('version', 'desc')->first();

        if (!$questionSet) {
            return response()->json(['error' => 'No questions available'], 404);
        }

        return response()->json([
            'id' => $questionSet->id,
            'version' => $questionSet->version,
            'questions' => $questionSet->questions,
        ]);
    }

    /**
     * Show the Thank You page.
     */
    public function thankYou()
    {
        return view('thank-you');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Response;
use App\Models\Question;

class ResponseController extends Controller
{
    /**
     * Display the responses in a paginated table with questions as headers.
     */
    public function index(Request $request)
    {
        $selectedVersion = $request->get('version', Question::max('version')); // Default to the latest version
        $questionSet = Question::where('version', $selectedVersion)->first();
        $questions = $questionSet ? $questionSet->questions : [];
        $responses = Response::paginate(10);

        $responses->transform(function ($response) {
            $response->answers = json_decode($response->answers, true); // Decode JSON answers
            return $response;
        });

        $versions = Question::distinct()->pluck('version'); // Fetch all available versions

        return view('responses.index', compact('responses', 'questions', 'versions', 'selectedVersion'));
    }

    public function analytics(Request $request)
    {
        $selectedVersion = $request->get('version', Question::max('version')); // Default to latest version
        $questionSet = Question::where('version', $selectedVersion)->first();
        $questions = $questionSet ? $questionSet->questions : [];

        // Filter responses by date range if provided
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $query = Response::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $responses = $query->get(); // Fetch filtered responses
        $totalParticipants = $responses->count();

        // Prepare analytics data
        $analyticsData = [];
        foreach ($questions as $question) {
            $analyticsData[$question['id']] = [
                'question' => $question['question'],
                'answers' => collect($responses)->map(function ($response) use ($question) {
                    $decodedAnswers = json_decode($response->answers, true);
                    return $decodedAnswers[$question['id']] ?? null;
                })->filter()->countBy()->toArray(), // Count occurrences of each answer
            ];
        }

        // Prepare correlation data
        $correlationData = [];
        foreach ($questions as $parentQuestion) {
            $parentQuestionId = $parentQuestion['id'];
            $correlationData[$parentQuestionId] = [
                'question' => $parentQuestion['question'],
                'answers' => [],
            ];

            // Get answers for the parent question
            $parentAnswers = collect($responses)->map(function ($response) use ($parentQuestion) {
                $decodedAnswers = json_decode($response->answers, true);
                return $decodedAnswers[$parentQuestion['id']] ?? null;
            })->filter()->countBy();

            foreach ($parentAnswers as $answer => $count) {
                $childData = [];
                foreach ($questions as $childQuestion) {
                    if ($childQuestion['id'] <= $parentQuestionId) continue; // Skip parent or earlier questions
                    $childAnswers = collect($responses)->filter(function ($response) use ($parentQuestion, $answer) {
                        $decodedAnswers = json_decode($response->answers, true);
                        return ($decodedAnswers[$parentQuestion['id']] ?? null) === $answer;
                    })->map(function ($response) use ($childQuestion) {
                        $decodedAnswers = json_decode($response->answers, true);
                        return $decodedAnswers[$childQuestion['id']] ?? null;
                    })->filter()->countBy();

                    $childData[$childQuestion['id']] = $childAnswers;
                }

                $correlationData[$parentQuestionId]['answers'][$answer] = [
                    'count' => $count,
                    'children' => $childData,
                ];
            }
        }

        $versions = Question::distinct()->pluck('version'); // Fetch all available versions

        return view('analytics.index', compact('analyticsData', 'questions', 'versions', 'selectedVersion', 'totalParticipants', 'startDate', 'endDate', 'correlationData'));
    }

}

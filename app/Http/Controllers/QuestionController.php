<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class QuestionController extends Controller
{
    public function index($step = 1)
    {
        if (!Storage::exists('questions.json')) {
            dd('Questions file not found!');
        }
        $questions = json_decode(Storage::get('questions.json'), true);
        $question = $questions[$step - 1] ?? null;

        if (!$question) {
            return redirect()->route('thank-you');
        }

        return view('question', compact('question', 'step'));
    }

    public function store(Request $request, $step)
    {
        // Retrieve existing answers from the session
        $data = $request->session()->get('answers', []);

        // Ensure the current step aligns with the question index
        $data[$step - 1] = $request->input('answer'); // Map the step to the corresponding index

        // Save the updated answers array back to the session
        $request->session()->put('answers', $data);

        // Load questions to determine if we're at the last step
        $questions = json_decode(Storage::get('questions.json'), true);

        if ($step >= count($questions)) {
            // Save responses in the database
            Response::create(['answers' => json_encode($data)]);

            // Clear the session
            $request->session()->forget('answers');

            return redirect()->route('thank-you');
        }

        return redirect()->route('questions', ['step' => $step + 1]);
    }


    public function showAllResponses()
    {
        // Get questions from the JSON file
        $questions = json_decode(Storage::get('questions.json'), true);

        // Fetch responses with pagination (10 per page)
        $responses = Response::paginate(10);

        // Pass paginated data to the view
        return view('responses.index', compact('questions', 'responses'));
    }


    public function thankYou()
    {
        return view('thank-you');
    }

    public function exportResponses()
    {
        // Retrieve questions from JSON file
        $questions = json_decode(Storage::get('questions.json'), true);

        // Fetch all responses from the database
        $responses = Response::all();

        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Prepare the header row
        $header = ['ID'];
        foreach ($questions as $question) {
            $header[] = $question['question'];
        }

        // Add the header row to the spreadsheet
        $sheet->fromArray($header, null, 'A1');

        // Add the user responses row by row
        $rowNumber = 2;
        foreach ($responses as $response) {
            $answers = json_decode($response->answers, true);

            // Start each row with the ID of the response
            $row = [$response->id];

            // Add answers for each question, or 'No response' if not available
            foreach ($questions as $index => $question) {
                $row[] = $answers[$index] ?? 'No response';
            }

            // Write the row to the sheet
            $sheet->fromArray($row, null, "A{$rowNumber}");
            $rowNumber++;
        }

        // Save the Excel file to a temporary location
        $writer = new Xlsx($spreadsheet);
        $fileName = 'responses.xlsx';
        $filePath = storage_path("app/{$fileName}");
        $writer->save($filePath);

        // Return the file as a download and delete it after sending
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}

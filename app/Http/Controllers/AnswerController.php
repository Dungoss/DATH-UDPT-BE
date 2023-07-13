<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
    //
    public function index()
    {
        $answers = DB::table('answer')->get();
        return response()->json($answers);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'questionID' => 'required',
            'userID' => 'required',
            'summaryContent' => 'required',
            'fullContent' => 'required',
            'postingTime' => 'required',
            'totalVotes' => 'required',
        ]);

        // Insert a new record into the questions table
        DB::table('answer')->insert([
            'questionID' => $validatedData['questionID'],
            'userID' => $validatedData['userID'],
            'summaryContent' => $validatedData['summaryContent'],
            'fullContent' => $validatedData['fullContent'],
            'postingTime' => $validatedData['postingTime'],
            'totalVotes' => $validatedData['totalVotes'],
        ]);

        // Return a JSON response
        return response()->json([
            'message' => 'Answer created successfully',
        ], 201);
    }
}

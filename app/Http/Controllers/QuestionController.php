<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    //
    public function index()
    {
        $questions = DB::table('question')
            ->orderBy('postingTime', 'desc')
            ->get();
        return response()->json($questions);
    }


    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'userID' => 'required',
            'questionContent' => 'required',
            'categoryID' => 'required',
            'totalVotes' => 'required',
            'postingTime' => 'required',
            'totalAnswer' => 'required',
            'statusApproved' => 'nullable|statusApproved',
        ]);

        // Insert a new record into the questions table
        DB::table('question')->insert([
            'userID' => $validatedData['userID'],
            'questionContent' => $validatedData['questionContent'],
            'categoryID' => $validatedData['categoryID'],
            'totalVotes' => $validatedData['totalVotes'],
            'postingTime' => $validatedData['postingTime'],
            'totalAnswer' => $validatedData['totalAnswer'],
            'statusApproved' => $validatedData['statusApproved'],
        ]);

        // Return a JSON response
        return response()->json([
            'message' => 'Question created successfully',
        ], 201);
    }

    public function destroy($id)
    {
        // Find the question by id
        $question = DB::table('question')->where('id', $id)->first();

        // Check if the question exists
        if (!$question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }

        // Delete the question
        DB::table('question')->where('id', $id)->delete();

        // Return a JSON response
        return response()->json([
            'message' => 'Question deleted successfully',
        ]);
    }
    public function updateStatusApproved(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'statusApproved' => 'required|in:0,1',
        ]);

        // Find the question by id
        $question = DB::table('question')->where('id', $id)->first();

        // Check if the question exists
        if (!$question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }

        // Update the statusApproved field
        DB::table('question')->where('id', $id)->update([
            'statusApproved' => $validatedData['statusApproved'],
        ]);

        // Return a JSON response
        return response()->json([
            'message' => 'Question status updated successfully',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    //
    public function index()
    {
        $users = DB::table('question')->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'id' => 'required',
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
            'id' => $validatedData['id'],
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
}

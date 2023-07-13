<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    //
    public function index()
    {
        $comments = DB::table('comment')->get();
        return response()->json($comments);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'userID' => 'required',
            'answerID' => 'required',
            'mentionedID' => 'required',
            'commentContent' => 'required',
            'postingTime' => 'required',
        ]);

        // Insert a new record into the questions table
        DB::table('comment')->insert([
            'userID' => $validatedData['userID'],
            'answerID' => $validatedData['answerID'],
            'mentionedID' => $validatedData['mentionedID'],
            'commentContent' => $validatedData['commentContent'],
            'postingTime' => $validatedData['postingTime'],
        ]);

        // Return a JSON response
        return response()->json([
            'message' => 'Comment created successfully',
        ], 201);
    }
}

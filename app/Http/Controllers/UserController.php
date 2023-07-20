<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = DB::table('users')->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'categoryName' => 'required',
        ]);
        DB::table('category')->insert([
            'categoryName' => $validatedData['categoryName'],
        ]);
        return response()->json([
            'message' => 'category created successfully',
        ], 201);
    }

    public function getQuestionIDsByUserID($userID)
    {
        $questionIDs = DB::table('user_question_spam')
            ->where('userID', $userID)
            ->pluck('questionID')
            ->all();

        return response()->json($questionIDs);
    }
}

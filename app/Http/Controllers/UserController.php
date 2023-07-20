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

    public function storeQuestionSpam(Request $request)
    {
        $validatedData = $request->validate([
            'userID' => 'required',
            'questionID' => 'required',
        ]);

        DB::table('user_question_spam')->insert([
            'userID' => $validatedData['userID'],
            'questionID' => $validatedData['questionID'],
        ]);

        return response()->json([
            'message' => 'Item added to user_question_spam successfully',
        ], 201);
    }

    public function deleteQuestionSpam(Request $request)
    {
        $validatedData = $request->validate([
            'userID' => 'required',
            'questionID' => 'required',
        ]);

        $deletedRows = DB::table('user_question_spam')
            ->where('userID', $validatedData['userID'])
            ->where('questionID', $validatedData['questionID'])
            ->delete();

        if ($deletedRows > 0) {
            return response()->json([
                'message' => 'Item deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Item not found',
            ], 404);
        }
    }
}

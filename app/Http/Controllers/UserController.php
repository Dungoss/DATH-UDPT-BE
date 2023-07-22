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

    public function findUser($userID)
    {
        $user = DB::table('users')->find($userID);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        return response()->json($user);
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

    public function updateAvatar(Request $request, $userID)
    {
        $validatedData = $request->validate([
            'avatar' => 'required|url',
        ]);

        $user = DB::table('users')->where('id', $userID)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        DB::table('users')
            ->where('id', $userID)
            ->update([
                'avatar' => $validatedData['avatar'],
            ]);

        return response()->json([
            'message' => 'Avatar updated successfully',
        ], 200);
    }

    public function updateWallpaper(Request $request, $userID)
    {
        $validatedData = $request->validate([
            'wallpaper' => 'required|url',
        ]);

        $user = DB::table('users')->where('id', $userID)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        DB::table('users')
            ->where('id', $userID)
            ->update([
                'wallpaper' => $validatedData['wallpaper'],
            ]);

        return response()->json([
            'message' => 'Wallpaper updated successfully',
        ], 200);
    }

    public function getQuestionsByUserID($userID)
    {
        $questions = DB::table('question')
            ->where('userID', $userID)
            ->get();

        return response()->json($questions);
    }

    public function increaseQuestionCount($userID)
    {
        $user = DB::table('users')->find($userID);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        DB::table('users')
            ->where('id', $userID)
            ->update([
                'question_count' => $user->question_count + 1,
            ]);

        return response()->json([
            'message' => 'question_count increased successfully',
        ], 200);
    }

    public function decreaseQuestionCount($userID)
    {
        $user = DB::table('users')->find($userID);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $newQuestionCount = max(0, $user->question_count - 1);

        DB::table('users')
            ->where('id', $userID)
            ->update([
                'question_count' => $newQuestionCount,
            ]);

        return response()->json([
            'message' => 'question_count decreased successfully',
        ], 200);
    }

    public function increaseAnswerCount($userID)
    {
        $user = DB::table('users')->find($userID);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        DB::table('users')
            ->where('id', $userID)
            ->update([
                'answer_count' => $user->answer_count + 1,
            ]);

        return response()->json([
            'message' => 'answer_count increased successfully',
        ], 200);
    }

    public function decreaseAnswerCount($userID)
    {
        $user = DB::table('users')->find($userID);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $newAnswerCount = max(0, $user->answer_count - 1);

        DB::table('users')
            ->where('id', $userID)
            ->update([
                'answer_count' => $newAnswerCount,
            ]);

        return response()->json([
            'message' => 'answer_count decreased successfully',
        ], 200);
    }
}

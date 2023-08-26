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

    public function changePassword(Request $request, $userID)
    {
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
        ]);

        $user = DB::table('users')->where('id', $userID)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        if (!password_verify($validatedData['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect',
            ], 400);
        }

        $newPasswordHash = bcrypt($validatedData['new_password']);
        DB::table('users')
            ->where('id', $userID)
            ->update([
                'password' => $newPasswordHash,
            ]);

        return response()->json([
            'message' => 'Password changed successfully',
        ], 200);
    }


    public function getQuestionIDsByUserID($userID)
    {
        $questionIDs = DB::table('user_question_spam')
            ->where('userID', $userID)
            ->pluck('questionID')
            ->all();

        return response()->json($questionIDs);
    }

    public function getUserStarForQuestions($userID)
    {
        $questionIDs = DB::table('user_question_star')
            ->where('userID', $userID)
            ->pluck('star', 'questionID')
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

    public function storeQuestionStar(Request $request)
    {
        $validatedData = $request->validate([
            'userID' => 'required',
            'questionID' => 'required',
            'star' => 'required',
        ]);

        $userID = $validatedData['userID'];
        $questionID = $validatedData['questionID'];
        $star = $validatedData['star'];

        $existingData = DB::table('user_question_star')
            ->where('userID', $userID)
            ->where('questionID', $questionID)
            ->first();

        if ($existingData) {
            DB::table('user_question_star')
                ->where('userID', $userID)
                ->where('questionID', $questionID)
                ->update(['star' => $star]);
        } else {
            DB::table('user_question_star')->insert([
                'userID' => $userID,
                'questionID' => $questionID,
                'star' => $star,
            ]);
        }

        DB::table('question')
            ->where('id', $questionID)
            ->increment('totalVotes');

        return response()->json([
            'message' => 'Item added to user_question_star successfully',
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

    public function getAdminAcceptNoti()
    {
        $adminUsers = DB::table('users')
            ->where('role', '=', 'admin')
            ->where('accept_noti', '=', '1')
            ->pluck('email');

        return response()->json($adminUsers);
    }

    public function updateAcceptNoti(Request $request, $id)
    {
        $request->validate([
            'accept_noti' => 'required|integer|in:0,1', // Accepts only 0 or 1 as integers
        ]);

        $acceptNoti = $request->input('accept_noti');

        try {
            DB::table('users')
                ->where('id', $id)
                ->update(['accept_noti' => $acceptNoti]);

            return response()->json(['message' => 'Accept notification updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update accept notification.'], 500);
        }
    }
}

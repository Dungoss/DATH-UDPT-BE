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
        $validatedData = $request->validate([
            'userID' => 'required',
            'questionTitle' => 'required',
            'questionContent' => 'required',
            'categoryID' => 'required',
            'totalVotes' => 'required',
            'postingTime' => 'required',
            'totalAnswer' => 'required',
            'statusApproved' => 'required',
            'tagID' => 'required',
            'spam' => 'required',
        ]);
        DB::table('question')->insert([
            'userID' => $validatedData['userID'],
            'questionTitle' => $validatedData['questionTitle'],
            'questionContent' => $validatedData['questionContent'],
            'categoryID' => $validatedData['categoryID'],
            'totalVotes' => $validatedData['totalVotes'],
            'postingTime' => $validatedData['postingTime'],
            'totalAnswer' => $validatedData['totalAnswer'],
            'statusApproved' => $validatedData['statusApproved'],
            'tagID' => $validatedData['tagID'],
            'spam' => $validatedData['spam'],
        ]);
        return response()->json([
            'message' => 'Question created successfully',
        ], 201);
    }

    public function destroy($id)
    {
        $question = DB::table('question')->where('id', $id)->first();
        if (!$question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }
        DB::table('question')->where('id', $id)->delete();
        return response()->json([
            'message' => 'Question deleted successfully',
        ]);
    }
    public function updateStatusApproved(Request $request, $id)
    {
        $validatedData = $request->validate([
            'statusApproved' => 'required|in:0,1',
        ]);
        $question = DB::table('question')->where('id', $id)->first();
        if (!$question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }
        DB::table('question')->where('id', $id)->update([
            'statusApproved' => $validatedData['statusApproved'],
        ]);
        return response()->json([
            'message' => 'Question status updated successfully',
        ]);
    }

    public function increaseSpamCount($id)
    {
        $question = DB::table('question')->where('id', $id)->first();
        if (!$question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }
        DB::table('question')->where('id', $id)->update([
            'spam' => $question->spam + 1,
        ]);
        return response()->json([
            'message' => 'Spam count increased successfully',
        ]);
    }

    public function decreaseSpamCount($id)
    {
        $question = DB::table('question')->where('id', $id)->first();
        if (!$question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }
        DB::table('question')->where('id', $id)->update([
            'spam' => $question->spam - 1,
        ]);
        return response()->json([
            'message' => 'Spam count decreased successfully',
        ]);
    }

    public function getMonthlyRanking()
    {
        // Get the current Unix timestamp
        $currentTimestamp = time();

        // Calculate the Unix timestamp of 1 month ago from the current time
        $oneMonthAgoTimestamp = $currentTimestamp - (30 * 24 * 60 * 60); // 30 days * 24 hours * 60 minutes * 60 seconds

        // Fetch questions where postingTime is within 1 month from now
        $questions = DB::table('question')
            ->where('postingTime', '>=', $oneMonthAgoTimestamp)
            ->orderBy('postingTime', 'desc')
            ->get();

        // Count the number of questions for each userID
        $userQuestionCounts = [];
        foreach ($questions as $question) {
            $userID = $question->userID;
            if (isset($userQuestionCounts[$userID])) {
                $userQuestionCounts[$userID]++;
            } else {
                $userQuestionCounts[$userID] = 1;
            }
        }

        // Get the user data for each userID
        $usersData = [];
        foreach ($userQuestionCounts as $userID => $numQuest) {
            $userData = DB::table('users')->where('id', $userID)->first();
            if ($userData) {
                $usersData[] = [
                    'userID' => $userID,
                    'num_quest' => $numQuest,
                    'user_data' => $userData,
                ];
            }
        }

        usort($usersData, function ($a, $b) {
            return $b['num_quest'] - $a['num_quest'];
        });

        return response()->json($usersData);
    }
}

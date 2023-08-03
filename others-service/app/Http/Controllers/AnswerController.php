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

        // Insert a new record into the answer table
        DB::table('answer')->insert([
            'questionID' => $validatedData['questionID'],
            'userID' => $validatedData['userID'],
            'summaryContent' => $validatedData['summaryContent'],
            'fullContent' => $validatedData['fullContent'],
            'postingTime' => $validatedData['postingTime'],
            'totalVotes' => $validatedData['totalVotes'],
        ]);

        DB::table('question')
            ->where('id', $validatedData['questionID'])
            ->increment('totalAnswer');

        return response()->json([
            'message' => 'Answer created successfully',
        ], 201);
    }

    public function getMonthlyRanking()
    {
        // Get the current Unix timestamp
        $currentTimestamp = time();

        // Calculate the Unix timestamp of 1 month ago from the current time
        $oneMonthAgoTimestamp = $currentTimestamp - (30 * 24 * 60 * 60); // 30 days * 24 hours * 60 minutes * 60 seconds

        // Fetch answer where postingTime is within 1 month from now
        $answer = DB::table('answer')
            ->where('postingTime', '>=', $oneMonthAgoTimestamp)
            ->orderBy('postingTime', 'desc')
            ->get();

        // Count the number of answer for each userID
        $userAnswerCounts = [];
        foreach ($answer as $answer) {
            $userID = $answer->userID;
            if (isset($userAnswerCounts[$userID])) {
                $userAnswerCounts[$userID]++;
            } else {
                $userAnswerCounts[$userID] = 1;
            }
        }

        // Format the result as { userID: 1, num_quest: 10 }
        $result = [];
        foreach ($userAnswerCounts as $userID => $numAns) {
            $userData = DB::table('users')->where('id', $userID)->first();
            if ($userData) {
                $result[] = [
                    'userID' => $userID,
                    'num_ans' => $numAns,
                    'user_data' => $userData,
                ];
            }
        }

        usort($result, function ($a, $b) {
            return $b['num_ans'] - $a['num_ans'];
        });

        return response()->json($result);
    }
}

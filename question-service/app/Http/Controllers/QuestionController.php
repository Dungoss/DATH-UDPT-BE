<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

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
    
            $questions = DB::table('question')
                ->orderBy('postingTime', 'desc')
                ->get();
    
            $data = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $request->header('Authorization')
              ])
              ->post("http://localhost:8003/api/open-ai", [
                "question" => $validatedData['questionContent'],
              ])
              ->json();
            
            if($data) {
                $question = DB::table('question')
                ->orderByDesc('postingTime')
                ->first();
              
                // Insert a new record into the answer table
                DB::table('answer')->insert([
                    'questionID' => $question->id,
                    'userID' => 9,
                    'summaryContent' => $data,
                    'fullContent' => $data,
                ]);

                DB::table('question')
                    ->where('id', $question->id)
                    ->increment('totalAnswer');
    
            }
         
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
        $currentTimestamp = time();

        $oneMonthAgoTimestamp = $currentTimestamp - (30 * 24 * 60 * 60);

        $questions = DB::table('question')
            ->where('postingTime', '>=', $oneMonthAgoTimestamp)
            ->orderBy('postingTime', 'desc')
            ->get();

        $userQuestionCounts = [];
        foreach ($questions as $question) {
            $userID = $question->userID;
            if (isset($userQuestionCounts[$userID])) {
                $userQuestionCounts[$userID]++;
            } else {
                $userQuestionCounts[$userID] = 1;
            }
        }

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

    public function searchQuestionsByKeyword(Request $request)
    {

        $request->validate([
            'keyword' => 'required|string',
        ]);

        $keyword = $request->input('keyword');

        $questions = DB::table('question')
            ->where(function ($query) use ($keyword) {
                $query->where('questionTitle', 'like', '%' . $keyword . '%')
                    ->orWhere('questionContent', 'like', '%' . $keyword . '%');
            })
            ->orderBy('postingTime', 'desc')
            ->get();

        return response()->json($questions);
    }

    public function searchQuestionsByTagID(Request $request)
    {
        $request->validate([
            'tagID' => 'required|string',
        ]);

        $tagID = $request->input('tagID');

        $questions = DB::table('question')
            ->where('tagID', 'like', '%' . $tagID . '%')
            ->orderBy('postingTime', 'desc')
            ->get();

        return response()->json($questions);
    }

    public function searchQuestionsByCategoryID(Request $request)
    {
        $request->validate([
            'categoryID' => 'required|integer',
        ]);

        $categoryID = $request->input('categoryID');

        $questions = DB::table('question')
            ->where('categoryID', $categoryID)
            ->orderBy('postingTime', 'desc')
            ->get();

        return response()->json($questions);
    }


    public function autoApprove()
    {
        $bannedWords = Config::get('banned-word.banned_words');

        $questions = DB::table('question')
            ->where('statusApproved', 0)
            ->get();

        foreach ($questions as $question) {
            $titleContainsBannedWord = false;
            $contentContainsBannedWord = false;

            foreach ($bannedWords as $bannedWord) {
                if (stripos($question->questionTitle, $bannedWord) !== false) {
                    $titleContainsBannedWord = true;
                }

                if (stripos($question->questionContent, $bannedWord) !== false) {
                    $contentContainsBannedWord = true;
                }
            }

            if ($titleContainsBannedWord == false && $contentContainsBannedWord == false) {
                DB::table('question')
                    ->where('id', $question->id)
                    ->update(['statusApproved' => 1]);
            }
        }

        return response()->json($contentContainsBannedWord);
    }


    public function getTop5Category()
    {
        $categoryCounts = DB::table('question')
            ->select('categoryID', DB::raw('count(*) as count'))
            ->groupBy('categoryID')
            ->orderBy('count', 'desc')
            ->pluck('categoryID')
            ->take(5);

        $topCategories = DB::table('category')
            ->whereIn('categoryID', $categoryCounts)
            ->get();

        return response()->json($topCategories);
    }
}

<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
  
class OpenAIController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
        $search = $request["question"];
  
        $data = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.env('OPENAI_API_KEY'),
                  ])
                  ->post("https://api.openai.com/v1/chat/completions", [
                    "model" => "gpt-3.5-turbo",
                    'messages' => [
                        [
                           "role" => "user",
                           "content" => $search
                       ]
                    ],
                  ])
                  ->json();
  
        return response()->json($data['choices'][0]['message'], 200, array(), JSON_PRETTY_PRINT);

    }
}
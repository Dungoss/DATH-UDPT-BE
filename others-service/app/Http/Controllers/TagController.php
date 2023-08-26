<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    //
    public function index()
    {
        $categorys = DB::table('tag')->get();
        return response()->json($categorys);
    }

    public function getTag()
    {
        $categories = DB::table('tag')->distinct('categoryID')->pluck('categoryID');

        $tags = [];

        foreach ($categories as $category) {
            $tagsInCategory = DB::table('tag')->where('categoryID', $category)->take(3)->get()->toArray();
            $tags = array_merge($tags, $tagsInCategory);
        }

        return response()->json($tags);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'tagName' => 'required',
        ]);

        // Insert a new record into the questions table
        DB::table('tag')->insert([
            'tagName' => $validatedData['tagName'],
        ]);

        // Return a JSON response
        return response()->json([
            'message' => 'Tag created successfully',
        ], 201);
    }
}

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

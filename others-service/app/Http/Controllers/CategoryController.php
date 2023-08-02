<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categorys = DB::table('category')->get();
        return response()->json($categorys);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'categoryName' => 'required',
        ]);

        // Insert a new record into the questions table
        DB::table('category')->insert([
            'categoryName' => $validatedData['categoryName'],
        ]);

        // Return a JSON response
        return response()->json([
            'message' => 'category created successfully',
        ], 201);
    }
}

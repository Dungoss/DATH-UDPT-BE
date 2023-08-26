<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('comments', [CommentController::class, 'index']);
Route::post('comments', [CommentController::class, 'store']);

Route::get('answers', [AnswerController::class, 'index']);
Route::post('answers', [AnswerController::class, 'store']);
Route::get('answers/monthly-ranking', [AnswerController::class, 'getMonthlyRanking']);

Route::get('category', [CategoryController::class, 'index']);
Route::post('category', [CategoryController::class, 'store']);

Route::get('tag', [TagController::class, 'index']);
Route::get('tag-cloud', [TagController::class, 'getTag']);
Route::post('tag', [TagController::class, 'store']);

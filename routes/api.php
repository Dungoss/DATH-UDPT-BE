<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}/question-spam', [UserController::class, 'getQuestionIDsByUserID']);

Route::get('questions', [QuestionController::class, 'index']);
Route::post('questions', [QuestionController::class, 'store']);
Route::delete('questions/{id}', [QuestionController::class, 'destroy']);
Route::put('questions/{id}/status', [QuestionController::class, 'updateStatusApproved']);
Route::post('questions/{id}/spam', [QuestionController::class, 'increaseSpamCount']);
Route::post('questions/{id}/not-spam', [QuestionController::class, 'decreaseSpamCount']);


Route::get('comments', [CommentController::class, 'index']);
Route::post('comments', [CommentController::class, 'store']);

Route::get('answers', [AnswerController::class, 'index']);
Route::post('answers', [AnswerController::class, 'store']);

Route::get('category', [CategoryController::class, 'index']);
Route::post('category', [CategoryController::class, 'store']);

Route::get('tag', [TagController::class, 'index']);
Route::post('tag', [TagController::class, 'store']);

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::group(['middleware' => 'api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

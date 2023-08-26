<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;

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

Route::get('questions', [QuestionController::class, 'index']);
Route::get('questions/popular', [QuestionController::class, 'filterPopular']);
Route::post('questions', [QuestionController::class, 'store']);
Route::get('questions/treding-category', [QuestionController::class, 'getTop5Category']);
Route::delete('questions/{id}', [QuestionController::class, 'destroy']);
Route::put('questions/{id}/status', [QuestionController::class, 'updateStatusApproved']);
Route::put('questions/auto-approve', [QuestionController::class, 'autoApprove']);
Route::post('questions/{id}/spam', [QuestionController::class, 'increaseSpamCount']);
Route::post('questions/{id}/not-spam', [QuestionController::class, 'decreaseSpamCount']);
Route::get('questions/monthly-ranking', [QuestionController::class, 'getMonthlyRanking']);
Route::get('questions/search-keyword', [QuestionController::class, 'searchQuestionsByKeyword']);
Route::get('questions/search-tag', [QuestionController::class, 'searchQuestionsByTagID']);
Route::get('questions/search-category', [QuestionController::class, 'searchQuestionsBycategoryID']);

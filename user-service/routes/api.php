<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
Route::get('users/admin-email', [UserController::class, 'getAdminAcceptNoti']);
Route::get('users/{id}', [UserController::class, 'findUser']);
Route::get('users/{userID}/questions', [UserController::class, 'getQuestionsByUserID']);
Route::get('users/{id}/question-spam', [UserController::class, 'getQuestionIDsByUserID']);
Route::get('users/{id}/question-star', [UserController::class, 'getUserStarForQuestions']);
Route::post('users/add-spam', [UserController::class, 'storeQuestionSpam']);
Route::post('users/add-star', [UserController::class, 'storeQuestionStar']);
Route::post('users/delete-spam', [UserController::class, 'deleteQuestionSpam']);
Route::post('users/{id}/update-avatar', [UserController::class, 'updateAvatar']);
Route::post('users/{id}/update-wallpaper', [UserController::class, 'updateWallpaper']);
Route::put('users/{id}/increase-question-count', [UserController::class, 'increaseQuestionCount']);
Route::put('users/{id}/decrease-question-count', [UserController::class, 'decreaseQuestionCount']);
Route::put('users/{id}/increase-answer-count', [UserController::class, 'increaseAnswerCount']);
Route::put('users/{id}/decrease-answer-count', [UserController::class, 'decreaseAnswerCount']);
Route::put('users/{id}/accept-noti', [UserController::class, 'updateAcceptNoti']);
Route::post('users/{userID}/change-password', [UserController::class, 'changePassword']);

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);



Route::group(['middleware' => 'api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\ConversationsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {

    Route::get('conversations', [ConversationsController::class, 'index']);
    Route::get('conversations/{conversation}', [ConversationsController::class, 'show']);
    Route::post('conversations/{conversation}/participants', [ConversationsController::class, 'addParticipant']);
    Route::delete('conversations/{conversation}/participants', [ConversationsController::class, 'removeParticipant']);

    Route::put('conversations/{conversation}/read', [ConversationsController::class, 'markAsRead']);


    Route::get('conversations/{id}/messages', [MessagesController::class, 'index']);
    Route::post('messages', [MessagesController::class, 'store'])
        ->name('api.messages.store');
    Route::delete('messages/{id}', [MessagesController::class, 'destroy']);
    Route::get('friends', [MessengerController::class, 'index']);
    Route::get('user', [MessengerController::class, 'getUser']);
});

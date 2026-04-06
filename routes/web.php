<?php

use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

require __DIR__ . '/auth.php';


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');

    // 2FA routes
    Route::post('/2fa/enable', [\App\Http\Controllers\Auth\TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/confirm', [\App\Http\Controllers\Auth\TwoFactorController::class, 'confirm'])->name('2fa.confirm');
    Route::post('/2fa/disable', [\App\Http\Controllers\Auth\TwoFactorController::class, 'disable'])->name('2fa.disable');
});
// مسارات المحادثات
Route::prefix('conversations')->group(function () {
    Route::get('/', [ConversationsController::class, 'index']);
    Route::post('/', [ConversationsController::class, 'store']);
    Route::get('/{id}', [ConversationsController::class, 'show']);
    Route::put('/{id}/read', [ConversationsController::class, 'markAsRead'])->where('id', '[0-9]+');
    Route::post('/{conversation}/participants', [ConversationsController::class, 'addParticipant']);
    Route::delete('/{conversation}/participants', [ConversationsController::class, 'removeParticipant']);
    Route::get('/{id}/messages', [MessagesController::class, 'index']);
});

// مسارات الرسائل والبيانات الأخرى
Route::post('messages', [MessagesController::class, 'store'])->name('messages.store');
Route::delete('messages/{id}', [MessagesController::class, 'destroy']);
Route::get('friends', [MessengerController::class, 'index']);
Route::get('current-user', [MessengerController::class, 'getUser']); // غيرنا الاسم لتمييزه


Route::get('/{id?}', [MessengerController::class, 'index'])->middleware('auth')->name('messenger');

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('guest')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    //     ->name('password.email');
});

Route::post('subscribe/handling', [SubscriptionController::class, 'handling']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('books')->group(function () {
        Route::get('/', [BookController::class, 'index']);
        Route::get('/{book}', [BookController::class, 'show']);
        Route::post('/{book}/bookmark', [BookController::class, 'bookmarkBook']);
    });

    Route::get('bookmarks', [BookController::class, 'getAllBookmarks']);

    Route::post('subscribe/{option}', [SubscriptionController::class, 'pay']);
    Route::get('subscriptions', [SubscriptionController::class, 'index']);

    Route::post('/profile', [AuthController::class, 'changeProfile']);
});

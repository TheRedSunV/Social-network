<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
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

Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function(){
    Route::prefix('friends')->group( function() {
        Route::get('/all', [FriendController::class, 'all'])->name('friends.all');
        Route::get('/friendship', [FriendController::class, 'get'])->name('friends.friendship');
        Route::post('/add', [FriendController::class, 'sendOrAcceptFriendRequest'])->name('friends.send_or_accept');
        Route::post('/cancel', [FriendController::class, 'cancelOrRejectFriendRequest'])->name('friends.cancel_or_reject');
        Route::delete('/delete', [FriendController::class, 'deleteFriend'])->name('friends.delete');
    });
});

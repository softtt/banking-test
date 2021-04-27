<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(\App\Http\Middleware\EnsureSignatureIsValid::class)->group(function () {
    Route::get(
        'currency/loaddaily',
        [\App\Http\Controllers\CurrencyController::class, 'loadDaily']
    );
    Route::get(
        'vk/setdailycurrencies',
        [\App\Http\Controllers\VkController::class, 'setCurrenciesInGroup']
    );

    Route::middleware(\App\Http\Middleware\ServiceFails::class)->group(function () {
        Route::get(
            'posts',
            [\App\Http\Controllers\VkController::class, 'getPosts']
        );
        Route::get(
            'profile',
            [\App\Http\Controllers\UserController::class, 'show']
        );
        Route::post(
            'profile',
            [\App\Http\Controllers\UserController::class, 'updateAvatar']
        );
        Route::get(
            'currencies',
            [\App\Http\Controllers\CurrencyController::class, 'index']
        );
        Route::get(
            'currency',
            [\App\Http\Controllers\CurrencyController::class, 'getCurrencyByDate']
        );
        Route::get(
            'notifications',
            [\App\Http\Controllers\NotificationController::class, 'getUserNotifications']
        );
    });
});

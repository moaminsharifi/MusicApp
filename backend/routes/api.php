<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class,'login'])->name('login');
    Route::post('signup', [AuthController::class,'signup'])->name('signup');

    Route::group([
        'middleware' => 'auth:sanctum'
    ], function() {
        Route::get('logout', [AuthController::class,'logout'])->name('logout');
        Route::get('user', [AuthController::class,'user'])->name('userData');
    });
});


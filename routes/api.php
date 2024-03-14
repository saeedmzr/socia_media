<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});


Route::group(['middleware' => 'auth:sanctum'], function () {


    Route::group(['prefix' => 'auth'], function () {
        Route::get('get', [AuthController::class, 'get']);
        Route::post('logout', [AuthController::class, 'logout']);
    });


    Route::apiResource('posts', PostController::class);
    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {

    });

//    Route::post('/broadcasting/token', [AuthController::class, 'broadcastToken']);


});

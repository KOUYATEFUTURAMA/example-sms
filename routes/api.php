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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/signup', 'App\Http\Controllers\Api\AuthController@signup');
    Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');
});  
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/logout', 'App\Http\Controllers\Api\AuthController@logout');

    Route::post('/send-message', 'App\Http\Controllers\Api\MessageController@sendMessage');
    Route::get('/list-message-sent', 'App\Http\Controllers\Api\MessageController@listMessageSent');
    Route::get('/list-message-sent-by-date/{date}', 'App\Http\Controllers\Api\MessageController@listMessageSentByDate');
    Route::get('/get-message-details/{id}', 'App\Http\Controllers\Api\MessageController@getMessageDetail');
});


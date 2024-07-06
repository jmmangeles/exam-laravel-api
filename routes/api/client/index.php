<?php

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

Route::get('/', function () {
    return response('Ok!');
});

Route::group([
    'namespace' => 'Auth',
    'prefix' => 'auth'
], function() {
    Route::post('login', 'AuthController@login');
    Route::get('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');
    Route::post('register', 'Register\RegisterController');
});

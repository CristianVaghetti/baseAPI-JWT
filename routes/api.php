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

Route::prefix('auth')->controller('AuthController')->group(function() {
    Route::post('/logout', 'AuthController@logout');
    Route::post('/refresh', 'AuthController@refresh');
    Route::post('', 'AuthController@authenticate');
});

# Routes of user password
Route::post('/user/password/forgot', 'PasswordController@forgot');
Route::post('/user/password/reset', 'PasswordController@reset');

Route::middleware('jwt.authenticate')->group(function () {
    Route::get('/users', 'UserController@index');
    Route::get('/user/{id}', 'UserController@show');
    Route::post('/user', 'UserController@store');
    Route::post('/user/{id}', 'UserController@update');
    Route::post('/user/{id}/change-password', 'PasswordController@change');
});
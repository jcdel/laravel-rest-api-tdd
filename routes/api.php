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


Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {

    // Access for Authenticated Users Only
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'AuthController@logout')->name('api.logout');
        Route::post('/tasks/create', 'TaskController@store')->name('api.tasks.create');
        Route::put('/tasks/{task}','TaskController@update')->name('api.tasks.update');
        Route::delete('/tasks/{task}','TaskController@destroy')->name('api.tasks.delete');
    });
    
    // Auth Routes
    Route::post('register', 'AuthController@register')->name('api.register');
    Route::post('login', 'AuthController@login')->name('api.login');

    // Tasks Routes
    Route::get('tasks', 'TaskController@index')->name('api.tasks');
    Route::get('tasks/{task}', 'TaskController@show')->name('api.tasks.show');

});
<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

        'middleware' => 'api',
        'prefix' => 'auth'

    ], function ($router) {

        Route::post('login', 'Auth\LoginController@login');
        Route::post('register', 'Auth\RegisterController@register');
        // Route::post('logout', 'AuthController@logout');
        // Route::post('refresh', 'AuthController@refresh');
        // Route::post('reset', 'AuthController@sendPasswordResetNotification');
        // Route::post('me', 'AuthController@me');
    });

//plaid
Route::post('addPlaid', 'PlaidController@addPlaid');
Route::post('eAuth', 'PlaidController@enterAuth');
Route::post('connect', 'PlaidController@connect');
// Route::post('exchange', 'PlaidController@exchange');
// Route::get('user', 'PlaidController@showUser');
// Route::get('categories', 'PlaidController@getCategories');
// Route::get('token', 'PlaidController@getToken');

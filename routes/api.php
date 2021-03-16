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

Route::post('jwt/login', 'AuthController@login');
Route::post('jwt/refresh-token', 'AuthController@refresh_token');



Route::get('departments/get', 'DepartmentController@index');
// Route::group([

//     'middleware' => 'api',
//     'prefix' => 'auth'

// ], function ($router) {
//     Route::post('api-login', 'AuthController@api_login');
//     Route::post('login', 'AuthController@login');
//     Route::post('logout', 'AuthController@logout');
//     Route::post('refresh', 'AuthController@refresh');
//     Route::post('me', 'AuthController@me');

// });


Route::group(['middleware' => 'jwt'], function () { 
    
    Route::get('job-types/get', 'JobTypeController@index');
    Route::get('sections/get', 'SectionController@index');
    Route::get('departments/get', 'DepartmentController@index');

    Route::get('customer-types/get', 'CustomerTypeController@index');
    Route::get('vehicle/get-by-vin/{vin}', 'VinController@show');
    
    Route::post('job-order/submit', 'JobOrderController@store');
});



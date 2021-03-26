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

// Route::post('jwt/login', 'AuthController@login');
// Route::post('jwt/refresh-token', 'AuthController@refresh_token');


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('api-login', 'AuthController@api_login');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});
;

Route::group(['middleware' => 'jwt'], function () { 
    
    Route::get('job-types/get', 'JobTypeController@index');
    Route::get('sections/get', 'SectionController@index');
    Route::get('departments/get', 'DepartmentController@index');

    Route::get('customer-types/get', 'CustomerTypeController@index');
    Route::get('vehicle/get-by-vin/{vin}', 'VinController@show');
    
    Route::post('job-order/submit', 'JobOrderController@store');
    Route::post('job-order/update', 'JobOrderController@update');

    Route::get('admin/job-order/list', 'JobOrderController@index');
    Route::get('job-order/header/get/{job_order_id}', 'JobOrderController@show');
    Route::get('job-order/line/get/{job_order_id}', 'JobOrderLineController@index');

    Route::get('job-order/approval/{job_order_id}', 'ApprovalController@index');

});

// public apis
Route::get('job-order/approve/{approval_id}', 'JOApproveController@store');
Route::get('job-order/reject-form/{approval_id}', 'JORejectController@show');
Route::post('job-order/reject', 'JORejectController@store');



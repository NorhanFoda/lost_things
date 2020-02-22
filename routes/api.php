<?php

use Illuminate\Http\Request;

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () {

    Route::post('login', 'api\AuthController@login');
    Route::post('logout', 'api\AuthController@logout');
    Route::post('refresh', 'api\AuthController@refresh');
    Route::post('change_password', 'api\AuthController@changePassword');
    Route::post('me', 'api\AuthController@me');
});

Route::post('signup', 'api\AuthController@signup');
Route::post('verify', 'api\AuthController@verify');
Route::post('resend_code', 'api\AuthController@resendCode');
Route::post('reset_password', 'api\ResetPasswordController@sendEmail');
Route::post('reset_password_add_new', 'api\ChangePasswordController@AddNewPasswordReseted');

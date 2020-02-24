<?php

use Illuminate\Http\Request;

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () {

    //User authorized links
    Route::post('login', 'api\AuthController@login');
    Route::post('logout', 'api\AuthController@logout');
    Route::post('refresh', 'api\AuthController@refresh');
    Route::post('change_password', 'api\AuthController@changePassword');
    Route::post('me', 'api\AuthController@me');

    //Main page links
    Route::apiResource('losts', 'api\LostsController');
    Route::apiResource('founds', 'api\FoundsController');
    Route::apiResource('categories', 'api\CategoriesController');

    //Favorites
    Route::post('add_to_favorites', 'api\FavoritesController@addToFavorites');
    Route::post('remove_from_favorites', 'api\FavoritesController@removeFromFavories');

    //Block list
    Route::group(['prefix' => 'user'], function(){
        Route::get('{user}/block_list', 'api\BlockController@getBlockList');
        Route::post('{user}/block_user', 'api\BlockController@blockUser');
        Route::post('{user}/unblock_user', 'api\BlockController@unblockUser');
    });

    //Comments link
    Route::group(['prefix' => 'posts'], function(){
        Route::apiResource('{post}/comments', 'api\CommentsController');
    });

    //Edit profile
    Route::get('get_user_profile/{id}', 'api\EditProfileController@getUserProfile');
    Route::get('get_old_email', 'api\EditProfileController@getOldEmail');
    Route::post('change_email', 'api\ChangeEmail@changeEmail');
    Route::post('verify_email/{token}', 'api\ChangeEmail@verifyEmail');

});

//user unauthorized links
Route::post('signup', 'api\AuthController@signup');
Route::post('verify', 'api\AuthController@verify');
Route::post('resend_code', 'api\AuthController@resendCode');
Route::post('reset_password', 'api\ResetPasswordController@sendEmail');
Route::post('reset_password_add_new', 'api\ChangePasswordController@AddNewPasswordReseted');

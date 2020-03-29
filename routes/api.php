<?php

use Illuminate\Http\Request;

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () {

    //User authorized links
    Route::post('login', 'api\AuthController@login');
    Route::post('login_phone', 'api\AuthController@loginPhone');
    Route::post('logout', 'api\AuthController@logout');
    Route::post('refresh', 'api\AuthController@refresh');
    Route::post('me', 'api\AuthController@me');

    //Main page links
    Route::apiResource('losts', 'api\LostsController');
    Route::apiResource('founds', 'api\FoundsController');
    Route::apiResource('categories', 'api\CategoriesController');

    //Favorites
    Route::post('add_to_favorites', 'api\FavoritesController@addToFavorites');
    Route::post('remove_from_favorites', 'api\FavoritesController@removeFromFavories');
    Route::get('get_favorites/{id}', 'api\FavoritesController@getFavorites');

    //Comments link
    Route::group(['prefix' => 'posts'], function(){
        Route::apiResource('{post}/comments', 'api\CommentsController');
    });

    //Edit profile
    Route::get('get_user_profile/{id}', 'api\EditProfileController@getUserProfile');
    //1-Change image
    Route::post('change_image/{id}', 'api\EditProfileController@changeImage');
    //2-Change name
    Route::post('change_name/{id}', 'api\EditProfileController@changeName');
    //3-Chnage location
    Route::post('change_location/{id}', 'api\EditProfileController@changeLocation');
    //4-Change date of birth
    Route::post('change_birth_date/{id}', 'api\EditProfileController@changeBirthDate');
    //5-Edit profile
    Route::post('edit_profile', 'api\EditProfileController@editProfile');

    //Setting
    //1-Change email
    Route::get('get_old_email', 'api\EditProfileController@getOldEmail');
    Route::post('change_email', 'api\ChangeEmailController@changeEmail');
    Route::post('verify_email/{token}', 'api\ChangeEmailController@verifyEmail');
    //2-Change password
    Route::post('change_password', 'api\AuthController@changePassword');
    //3-Avtivate - Deactivate notifications
    Route::post('activate_notification/{id}', 'api\EditProfileController@activateNotification');
    //4-Block list
    Route::group(['prefix' => 'user'], function(){
        Route::get('{user}/block_list', 'api\BlockController@getBlockList');
        Route::post('{user}/block_user', 'api\BlockController@blockUser');
        Route::post('{user}/unblock_user', 'api\BlockController@unblockUser');
    });
    //5-Change location
    Route::post('activate_location/{id}', 'api\EditProfileController@activateLocation');
    //6-Change language
    Route::post('change_lang/{id}', 'api\EditProfileController@changeLang');

    //Search
    Route::post('search', 'api\SearchController@search');

    //Chat
    Route::get('get_chats_list', 'api\ChatController@getChatsList');//index page
    Route::get('get_chat', 'api\ChatController@getChat');//get chat page
    Route::post('send_messages', 'api\ChatController@sendMessages');//send message to admin
    Route::delete('delete_chat', 'api\ChatController@deleteChat');

    //conditions and rules
    Route::get('get_conditions', 'api\ConditionsController@getConditions');
    
    //Notification tokens
    Route::get('/get_token/{id}', 'api\TokenController@index');
    Route::post('/create_token', 'api\TokenController@create');

    //Broadcast notifications
    Route::get('/get_notify_list', 'api\NotificationController@getNotifyList');
    Route::get('/get_notify_count', 'api\NotificationController@count');

});

//user unauthorized links
Route::post('signup', 'api\AuthController@signup');
Route::post('signup_phone', 'api\AuthController@signupPhone');
Route::post('verify', 'api\AuthController@verify');
Route::post('resend_code', 'api\AuthController@resendCode');
Route::post('reset_password', 'api\ResetPasswordController@sendEmail');
Route::post('reset_password_add_new', 'api\ChangePasswordController@AddNewPasswordReseted');

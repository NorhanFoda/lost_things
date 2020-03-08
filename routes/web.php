<?php

Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
	/** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
	
	Route::get('/', function () {
	\LaravelLocalization::setLocale('ar');
	$url = \LaravelLocalization::getLocalizedURL(App::getLocale(), \URL::previous());
		return Redirect::to($url);
	});

	//home page
	Route::get('/', 'dashboard\AdminHomeController@index')->middleware('auth:web');
	
	//Users
	Route::resource('users', 'dashboard\UserController')->middleware('auth:web');
	Route::post('delete', 'dashboard\UserController@delete')->name('users.delete')->middleware('auth:web');
	Route::post('block', 'dashboard\UserController@blockUser')->name('users.block')->middleware('auth:web');
	Route::get('get_blocked_users', 'dashboard\UserController@getBlockedUsers')->name('users.blockList')->middleware('auth:web');
	// Route::get('get_unblocked_users', 'dashboard\UserController@getUnblockedUsers')->name('users.unblockList');

	//posts
	//1-Losts
	Route::get('get_losts', 'dashboard\LostController@getLosts')->name('losts.getLosts')->middleware('auth:web');
	Route::get('show_lost/{id}', 'dashboard\LostController@showLost')->name('losts.showLost')->middleware('auth:web');
	Route::get('create_lost', 'dashboard\LostController@createLost')->name('losts.createLost')->middleware('auth:web');
	Route::post('store_lost', 'dashboard\LostController@storeLost')->name('losts.storeLost')->middleware('auth:web');
	Route::get('edit_lost/{id}', 'dashboard\LostController@editLost')->name('losts.editLost')->middleware('auth:web');
	Route::put('update_lost/{id}', 'dashboard\LostController@updateLost')->name('losts.updateLost')->middleware('auth:web');
	//2-Founds
	Route::get('get_founds', 'dashboard\FoundController@getfounds')->name('founds.getFounds')->middleware('auth:web');
	Route::get('show_found/{id}', 'dashboard\FoundController@showfound')->name('founds.showFound')->middleware('auth:web');
	Route::get('create_found', 'dashboard\FoundController@createfound')->name('founds.createFound')->middleware('auth:web');
	Route::post('store_found', 'dashboard\FoundController@storefound')->name('founds.storeFound')->middleware('auth:web');
	Route::get('edit_found/{id}', 'dashboard\FoundController@editfound')->name('founds.editFound')->middleware('auth:web');
	Route::put('update_found/{id}', 'dashboard\FoundController@updatefound')->name('founds.updateFound')->middleware('auth:web');
	//3-delete post
	Route::post('delete_post', 'dashboard\LostController@deletePost')->name('losts.delete')->middleware('auth:web');

	//conditions and rules
	Route::resource('conditions', 'dashboard\ConditionsController')->middleware('auth:web');
	Route::post('delete_condition', 'dashboard\ConditionsController@deleteCondition')->name('conditions.delete')->middleware('auth:web');

	//comments
	Route::post('delete_comment', 'dashboard\CommentController@deleteComment')->name('comments.delete');

	//Chat
	Route::get('get_messages', 'dashboard\MessageController@getMessages')->name('chat.index')->middleware('auth:web');
	Route::get('get_chat_page/{id}', 'dashboard\MessageController@getChatPage')->name('chat.getChat')->middleware('auth:web');
	Route::post('chat', 'dashboard\MessageController@store')->name('chat.store')->middleware('auth:web');
	// Route::post('chat/join', 'dashboard\MessageController@join')->name('chat.join')->middleware('auth:web');
	Route::post('delete_chat', 'dashboard\MessageController@deleteChat')->name('chats.delete')->middleware('auth:web');

	//change lang
	Route::get('change_locale/{locale}', 'dashboard\AdminController@change_locale')->name('change_locale');

	Auth::routes();
});

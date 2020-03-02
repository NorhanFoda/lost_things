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
	Route::resource('losts', 'dashboard\LostController')->middleware('auth:web');//losts
	Route::resource('founds', 'dashboard\FOundController')->middleware('auth:web');//founds
	Route::post('delete_post', 'dashboard\LostController@deletePost')->name('losts.delete')->middleware('auth:web');

	//conditions and rules
	Route::resource('conditions', 'dashboard\ConditionsController')->middleware('auth:web');
	Route::post('delete_condition', 'dashboard\ConditionsController@deleteCondition')->name('conditions.delete')->middleware('auth:web');

	//change lang
	Route::get('change_locale/{locale}', 'dashboard\AdminController@change_locale')->name('change_locale');

	Auth::routes();
});

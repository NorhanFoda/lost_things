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
	Route::get('/', 'dashboard\AdminHomeController@index');
	
	//Users
	Route::resource('users', 'dashboard\UserController');
	Route::post('delete', 'dashboard\UserController@delete')->name('users.delete');
	Route::post('block', 'dashboard\UserController@blockUser')->name('users.block');
	Route::get('get_blocked_users', 'dashboard\UserController@getBlockedUsers')->name('users.blockList');
	// Route::get('get_unblocked_users', 'dashboard\UserController@getUnblockedUsers')->name('users.unblockList');

	//posts
	Route::resource('losts', 'dashboard\LostController');//losts
	Route::resource('founds', 'dashboard\FOundController');//founds
	Route::post('delete_post', 'dashboard\LostController@deletePost')->name('losts.delete');


	//login
	// Route::get('/admin/login', 'dashboard\AdminController@login')->name('admin_login');
	// Route::post('dashboard', 'dashboard\AdminController@admin_login')->name('dashboard');

	//conditions and rules
	Route::resource('conditions', 'dashboard\ConditionsController');
	Route::post('delete_condition', 'dashboard\ConditionsController@deleteCondition')->name('conditions.delete');

	//change lang
	Route::get('change_locale/{locale}', 'dashboard\AdminController@change_locale')->name('change_locale');
});

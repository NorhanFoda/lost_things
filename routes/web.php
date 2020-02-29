<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
	/** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
	Route::get('/', function()
	{
		return view('welcome');
	});
	
	//Users
	Route::resource('users', 'dashboard\UserController');
	Route::post('block', 'dashboard\UserController@blockUser')->name('users.block');
	Route::get('get_blocked_users', 'dashboard\UserController@getBlockedUsers')->name('users.blockList');
	// ROute::get('get_unblocked_users', 'dashboard\UserController@getUnblockedUsers')->name('users.unblockList');

	//losts
	Route::resource('losts', 'dashboard\LostController');
});
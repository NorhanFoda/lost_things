<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Auth;

class AdminController extends Controller
{

    public function change_locale($locale){
        \LaravelLocalization::setLocale($locale);
	    $url = \LaravelLocalization::getLocalizedURL(\App::getLocale(), \URL::previous());
		return \Redirect::to($url);
    }
}

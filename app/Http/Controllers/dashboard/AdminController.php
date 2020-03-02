<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Auth;

class AdminController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/users';

    public function login(){
        return view('admin.auth.login');
    }//end login

    public function admin_login(Request $request){
// dd($request->all());
        $credentials = $request->only('email', 'password');
      
        $is_admin = User::where('email', $request->email)->first()->is_admin;
      
        if (Auth::attempt($credentials)) {
           
            return redirect(route('users.index'));
        }
        return redirect('admin/login');
    }//end admin_login

    public function change_locale($locale){
        session(['locale'=>$locale]);
        return redirect()->back();
    }//end change_locale
}

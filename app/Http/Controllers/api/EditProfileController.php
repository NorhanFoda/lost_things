<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class EditProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getUserProfile($id){
  
        return response()->json([
            'data' => User::with('posts')->where('id', $id)->first()
        ], 200);
    }

    public function getOldEmail(){
        return response()->json([
            'data' => auth()->user()->email
        ], 200);
    }

}

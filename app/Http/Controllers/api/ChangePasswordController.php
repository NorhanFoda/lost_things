<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\DB;
use App\User;

class ChangePasswordController extends Controller
{
    public function AddNewPasswordReseted(ChangePasswordRequest $request){
        return $this->getPasswordResetTableRow($request) ? $this->changePassword($request) : 
            $this->tokenNotFoundResponse();
    }

    private function getPasswordResetTableRow($request){
        return DB::table('password_resets')->where(['email' => $request->email, 
                'token' => $request->resetToken]);
    }

    public function tokenNotFoundResponse(){
        return response()->json(['error' => 'Tokn or email incorrect'], 422);
    }

    public function changePassword($request){
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => bcrypt($request->password)]);
        $this->getPasswordResetTableRow($request)->delete();
        return response()->json(['data' => 'Password Successfuly Changed'], 201);
    }
    
}

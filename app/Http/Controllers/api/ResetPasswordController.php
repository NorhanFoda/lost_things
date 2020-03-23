<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function sendEmail(Request $request){
        
        if(!$this->validateEmail($request->email)){
            return $this->failedResponse();
        }

        $this->send($request->email);
        return $this->successResponse();
    }

    public function send($email){
        $user = User::where('email', $email)->first();
        if($user){
            $code = rand ( 1000 , 9999 );
            $user->update(['code' => $code]);
            $token = $this->createToken($email);
            Mail::to($email)->send(new ResetPasswordMail($token, $code));   
        }
    }

    public function createToken($email){

        $oldToken = DB::table('password_resets')->where('email', $email)->first();

        if($oldToken){
            return $oldToken;
        }

        $token = str_random(60);
        $this->saveToken($token, $email);
        return $token;
    }

    public function saveToken($token, $email){
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

    public function validateEmail($email){
        return !!User::where('email', $email)->first();
    }

    public function failedResponse(){
        return response()->json([
            'error' => 'Email not found'
        ], 404);
    }

    public function successResponse(){
        return response()->json([
            'data' => 'Reset email message is sent'
        ], 200);
    }

}

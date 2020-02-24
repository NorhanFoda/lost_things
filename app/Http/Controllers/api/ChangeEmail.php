<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChangeEmailMail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;

class ChangeEmail extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    //request must contain old email + password and new email
    public function changeEmail(Request $request){
        if(!$this->validateEmail($request->email)){
            return $this->failedResponse();
        }

        $this->send($request->email,$request->new_email);
        return $this->successResponse();
    }

    public function send($old_email,$new_email){
        $token = $this->createToken($old_email, $new_email);
        User::where('email', $old_email)->first()->update(['email'=> $new_email]);
        Mail::to($new_email)->send(new ChangeEmailMail($token));
    }

    public function createToken($old_email, $new_email){

        $oldToken = DB::table('password_resets')->where('email', $old_email)->first();

        if($oldToken){
            return $oldToken;
        }

        $token = str_random(60);
        $this->saveToken($token, $new_email);
        return $token;
    }

    public function saveToken($token, $email){
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

    public function verifyEmail($token){
        return $token;
    }


    public function validateEmail($email){
        // dd(auth()->user()->email);
        if(auth()->user()->email == $email){return true;}
        return false;
    }

    public function failedResponse(){
        return response()->json([
            'error' => 'Invalid email'
        ], 404);
    }

    public function successResponse(){
        return response()->json([
            'data' => 'Change email message is sent'
        ], 200);
    }

}

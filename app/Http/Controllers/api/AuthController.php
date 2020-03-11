<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendVerificationCode;
use App\Http\Requests\PhoneSignUpRequest;
use App\Http\Requests\EmailSignUpRequest;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup', 'verify', 'resendCode',
            'signupPhone', 'loginPhone'
        ]]);
    }

    public function signup(EmailSignUpRequest $request){

        $code = $this->createVerificationCode();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'code' => $code,
        ]);

        //send email with activation code
        return $this->sendEmail($user->email, $code);
    }

    public function signupPhone(PhoneSignUpRequest $request){
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'is_verified' => 1
        ]);
        return $this->loginPhone($request);
    }

    public function loginPhone(Request $request)
    {
        //user is not verified so he can not login
        $user = User::where('phone', $request->phone)->first();
        if($user->is_verified == 0){
            return response()->json([
                'error' => "this account is not verified"
            ], 400);
        }
        
        $credentials = $request->only('phone', 'password');
        
        if($user->token == null){
            $user->update(['token' => $this->guard()->attempt($credentials)]);
        }

        return $this->respondWithToken($user);

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function sendEmail($email, $code){
        $this->send($email, $code);
        return response()->json([
            'data' => 'Verification code email message is sent'
        ], 200);
    }

    public function send($email, $code){
        Mail::to($email)->send(new sendVerificationCode($code));
    }

    public function failedResponse(){
        return response()->json([
            'error' => 'Email not found'
        ], 404);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        //user is not verified so he can not login
        $user = User::where('email', $request->email)->first();
        if($user->is_verified == 0){
            return response()->json([
                'error' => "this account is not verified"
            ], 400);
        }

        $credentials = $request->only('email', 'password');

        if($user->token == null){
            $user->update(['token' => $this->guard()->attempt($credentials)]);
        }

        return $this->respondWithToken($user);

        // if ($token = $this->guard()->attempt($credentials)) {
        //     return $this->respondWithToken($token);
        // }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->user()->update(['token' => null]);
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->user()->token);
        // return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($user)
    {
        // dd($user);
        return response()->json([
            'access_token' => $user->token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    //Verify user
    public function verify(Request $request){
        
        if($request->email && $request->code){
            $user = User::where('email', $request->email)->first();
            $user_code = $user->code;
            $code = $request->code;
            if($user_code == $code){
                $user->is_verified = 1; 
                $user->save();
                return response()->json([
                    'data' => $this->login($request)
                ], 200);
            }
        }
        return response()->json([
            'error' => 'user_id and activation code are need'
        ], 400);
    }

    public function changePassword(Request $request){

        $credentials = $request->only('email', 'password');
        
        if ($token = $this->guard()->attempt($credentials)) {
            $user = auth()->user(); 
            if (Hash::check($request->password, $user->password)){ 
                $user->fill(['password' => Hash::make($request->new_password)])->save();
                return response(['data' => 'Password reset'], 200);
            }
            return response()->json([
                'error' => 'Password mismatch'
            ], 400);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function resendCode(Request $request){

        if($request->user_id && $request->email){
            if(!User::find($request->user_id)->is_verified){
                $code = $this->createVerificationCode();
                User::find($request->user_id)->update(['code' => $code]);
                //send email with new verification code
                $this->sendEmail($request->email, $code);
                return response()->json([
                    'data' => 'Verification code resent'
                ], 200);
            }
            return response()->json([
                'error' => 'User is already verified'
            ], 400);
        }
        return response()->json([
            'error' => 'user_id and email are required'
        ], 400);
    }

    private function createVerificationCode(){
        return rand ( 1000 , 9999 );
    }
}

<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendVerificationCode;
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
        $this->middleware('auth:api', ['except' => ['login', 'signup', 'verify', 'resendCode'
        ]]);
    }

    public function signup(Request $request){

        $code = $this->createVerificationCode();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'code' => $code,
        ]);

        //send email with activation code
        $this->sendEmail($user->email, $code);
        // return ['data' => 'Verification code sent'];
    }

    public function sendEmail($email, $code){
        $this->send($email, $code);
        return $this->successResponse();
    }

    public function send($email, $code){
        Mail::to($email)->send(new sendVerificationCode($code));
    }

    public function successResponse(){
        return response()->json([
            'data' => 'Verification code email message is sent'
        ], 200);
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
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

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
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => auth()->user()
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
                $user->update(['is_verified' => 1]);
                return $this->login($request);
            }
        }
        return ['error' => 'user_id and activation code are needed'];
    }

    public function changePassword(Request $request){

        $credentials = $request->only('email', 'password');
        
        if ($token = $this->guard()->attempt($credentials)) {
            $user = auth()->user(); 
            if (Hash::check($request->password, $user->password)){ 
                $user->fill(['password' => Hash::make($request->new_password)])->save();
                return response(['data' => 'Password reset'], 200);
            }
            return ['error' => 'Password mismatch'];
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function resendCode(Request $request){

        if($request->user_id && $request->email){
            $code = $this->createVerificationCode();
            User::find($request->user_id)->update(['code' => $code]);
            //send email with new verification code
            return ['data' => 'Verification code sent'];
        }
        return ['error' => 'user_id and email are required'];
    }

    private function createVerificationCode(){
        return rand ( 1000 , 9999 );
    }
}

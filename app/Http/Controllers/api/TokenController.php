<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Token;
use Illuminate\Support\Facades\Validator;

class TokenController extends Controller
{
    public function index($id){
        $user = User::find($id);
        if($user){
            if($user->notification_active == 0){
                return response()->json([
                    'errpr' => 'Notifications is inactive'
                ], 400);    
            }
            return response()->json([
                'data' => $user->tokens
            ], 200);    
        }
        return response()->json([
            'error' => "User not found"
        ], 400);    
        
    }

    public function create(Request $request){
        $this->validate($request, [
            'user_id' => 'required',
            'token' => 'required'
        ]);
    
        if($request->user_id == auth()->user()->id){
            $user_token = Token::where('user_id', $request->user_id)
                                ->where('token', $request->token)->first();
            if(!$user_token){
                $token = Token::create([
                    'user_id' => $request->user_id,
                    'token' => $request->token
                ]);
        
                if($token){
                    return response()->json([
                        'data' => $token
                    ], 201);
                }
                else{
                    return response()->json([
                        'error' => 'Creation failed'
                    ], 400);
                }   
            }
            else{
                return response()->json([
                    'error' => 'Token is repeated for this user'
                ], 400);
            }
        }
        
        return response()->json([
            'error' => 'Unauthorized'
        ], 400);

    }
}

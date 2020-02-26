<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function fetchMessages(){
        return response()->json([
            'date' => auth()->user()->messages
        ], 200);
    }

    public function sendMessages(Request $request){
        if($request->message != null){
            $user = auth()->user();
            $user->messages()->create([
                'message' => $request->message
            ]);
            return response()->json([
                'data' => 'Message sent'
            ], 201);
        }
        return response()->json([
            'error' => 'Message required'
        ], 400);
    }
}

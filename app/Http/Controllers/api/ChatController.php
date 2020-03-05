<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Message;
use App\Models\Chat;

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

    public function getUserMessages(Request $request){
        $this->validate($request,[
            'sender' => 'required',
            'receiver' => 'required'
        ]);

        $chat = Chat::where(['user1_id' => $request->sender, 'user2_id' => $request->receiver])
                ->orWhere(['user2_id' => $request->sender, 'user1_id' => $request->receiver])->first();
        if($chat){
           $msg = Message::where('chat_id', $chat->id) ->get();
           return response()->json([
                'data' => $msg
           ], 200);
        }
        else{
            return response()->json(null, 404);
        }
    }

    public function sendMessages(Request $request){
        $this->validate($request, [
            'sender' => 'required',
            'receiver' => 'required',
            'message' => 'required'
        ]);

        //find users chating channel
        $chat = Chat::where(['user1_id' => $request->sender, 'user2_id' => $request->receiver])
                    ->orWhere(['user2_id' => $request->sender, 'user1_id' => $request->receiver])->first();
        //if there is no channel then create one
        if(!$chat){
            $chat = Chat::create([
                'user1_id' => $request->sender, //sender
                'user2_id' => $request->receiver //receiver
            ]);
        }

        //create sender messages
        $sent = Message::create([
            'user_id' => $chat->user1_id,
            'message' => $request->message,
            'type' => 0,
            'chat_id' => $chat->id
        ]);

        //create reciever messages
        $received = Message::create([
            'user_id' => $chat->user2_id,
            'message' => $request->message,
            'type' => 1,
            'chat_id' => $chat->id
        ]);

        return response()->json([
            'data' => ['sent' => $sent, 'received' => $received]
        ], 201);
    }

    public function deleteMessages($id){
        Message::find($id)->delete();
        return response()->json(null, 204);
    }
}

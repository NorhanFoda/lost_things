<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Message;
use App\Models\Chat;
use Illuminate\Support\Facades\Validator;

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
            'message' => 'required_without:image',
            'image' => 'required_without:message'
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

        if($request->image != null){
            $imageRules = array(
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            );
            //Validate image
            $image_to_validate = array('image' => $request->image);
            $imageValidator = Validator::make($image_to_validate, $imageRules);
            if ($imageValidator->fails()) {
                return $imageValidator->messages();
            }

            //Make image name unique
            $full_file_name = $request->image;
            $file_name = pathinfo($full_file_name, PATHINFO_FILENAME);
            $extension = $request->image->getClientOriginalExtension();
            $file_name_to_store = $file_name.'_'.time().'.'.$extension;
            
            //Upload image
            $path = $request->image->move(public_path('/images/'), $file_name_to_store);
            $url = url('/images/'.$file_name_to_store);
            $image = $url;
        }
        else{
            $image = null;
        }

        //create sender messages
        $sent = Message::create([
            'user_id' => $chat->user1_id,
            'message' => $request->message,
            'image' => $image,
            'type' => 0,
            'chat_id' => $chat->id
        ]);

        //create reciever messages
        $received = Message::create([
            'user_id' => $chat->user2_id,
            'message' => $request->message,
            'image' => $image,
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

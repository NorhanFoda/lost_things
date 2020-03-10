<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Message;
use App\Models\Chat;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ChatResourceCollection;
use App\Http\Resources\ChatResource;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    //get all users with theri messages
    public function getChatsList(){
        $chats = Chat::where('user1_id', auth()->user()->id)->orWhere('user2_id', auth()->user()->id)->get();
        return response()->json([
            'data' => ChatResourceCollection::collection($chats)
        ], 200);
    }

    public function getChat(Request $request){
        $messages = Message::where('chat_id' , $request->chat_id)->get();
        return response()->json([
            'data' => new ChatResource(Chat::find($request->chat_id))
        ], 200);
    }

    public function sendMessages(Request $request){
        
        // dd($request->all());
        
        $this->validate($request, [
            'sender' => 'required',
            'receiver' => 'required',
            'message' => 'required_without:image',
            'image' => 'required_without:message',
            'chat_id' => 'sometimes'
        ]);

        if($request->sender != auth()->user()->id){
            return response()->json([
                'error' => 'not allowed to send messages'
            ], 400);
        }

        if($request->chat_id == null){
            $chat = Chat::Where([['user1_id' , $request->sender], ['user2_id' , $request->receiver]])
                                ->orWhere([['user2_id' , $request->sender] , ['user1_id' , $request->receiver]])->first();
            // dd($chat);
            if(!$chat){
                $chat = Chat::create([
                    'user1_id' => $request->sender, //sender
                    'user2_id' => $request->receiver //receiver
                ]);
            }
            $request->chat_id = $chat->id;
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
            'user_id' => $request->sender,
            'message' => $request->message,
            'image' => $image,
            'type' => 0,
            'chat_id' => $request->chat_id
        ]);

        //create reciever messages
        $received = Message::create([
            'user_id' => $request->receiver,
            'message' => $request->message,
            'image' => $image,
            'type' => 1,
            'chat_id' => $request->chat_id
        ]);

        return response()->json([
            'data' => ['sent' => $sent, 'received' => $received]
        ], 201);
    }

    public function deleteChat(Request $request){
        $this->validate($request, [
            'chat_id' => 'required'
        ]);
        Message::where('chat_id', $request->chat_id)->delete();
        Chat::find($request->chat_id)->delete();
        return response()->json(null, 204);
    }
}

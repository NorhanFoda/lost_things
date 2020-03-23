<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Notification;
use App\Models\Message;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Notifications\AccountActivated;
use App\Models\Chat;

class MessageController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('web');
    }

    public function getMessages(){
        // $chats = Chat::where('user1_id', auth()->user()->id)->orWhere('user2_id', auth()->user()->id)->get();
        // return view('admin.chat.index')->with('chats', $chats);
        return view('admin.chat.index');
    }

    public function getChatPage($id){
        $chat = Chat::find($id);
        if($chat->user1_id != auth()->user()->id){
            return view('admin.chat.chat')->with('messages', $chat->messages)
                                        ->with('chat_id', $id)
                                        ->with('user_id', $chat->user1_id);   
        }
        else{
            return view('admin.chat.chat')->with('messages', $chat->messages)
                                        ->with('chat_id', $id)
                                        ->with('user_id', $chat->user2_id);
        }
    }
    

    public function getChatUsers(Request $request){
        if($request->ajax()){
            $chat = Chat::find($request->id);
            $sender = null;
            if($chat->user1_id != auth()->user()->id){
                $sender = User::find($chat->user1_id);
            }
            else if($chat->user2_id != auth()->user()->id){
                $sender = User::find($chat->user2_id);
            }
            $last_msg = Message::where('chat_id', $request->id)->orderBy('id', 'desc')->get(['message', 'created_at'])->first();
            $sent_time = $last_msg->created_at->diffForHumans(\Carbon\Carbon::now());
            return response()->json([
                'chat_id' => $chat->id,
                'sender' => $sender,
                'last_msg' => $last_msg,
                'sent_time' => $sent_time
            ], 200);
        }
    }

    public function store(Request $request) {
        $chat = Chat::where('id', $request->chat_id)->first();

        if(!$chat){
            $chat = Chat::create([
                'user1_id' => $request->user2_id,  //sender
                'user2_id' =>  auth()->user()->id  //receiver
            ]);
        }

        //create sender
        //create sender messages
        $chat = Message::create([
            'user_id' => auth()->user()->id,
            'message' => $request->content,
            'type' => 0,
            'chat_id' => $request->chat_id
        ]);

        //create receiver
        $chat = Message::create([
            'user_id' => $request->user2_id,
            'message' => $request->content,
            'type' => 1,
            'chat_id' => $request->chat_id
        ]);
        
        // auth()->user()->notify(new AccountActivated);

		return response(['data' => $chat], 200);
    }
    
    public function join(Request $request) {
		$this->validate($request, [
			'id' => 'required'
		]);

		$input = $request->all();
		$input['message'] = 'join';
		// $input['ip'] = request()->ip();
		$input['type'] = 5;

		$chat = Message::create($input);
		return response(['data' => $chat], 200);
    }
    
    public function deleteChat(Request $request){
        if($request->ajax()){
            $messages = Message::where('chat_id', $request->id)->get();
            foreach($messages as $msg){
                $msg->delete();
            }
            Chat::find($request->id)->delete();
            session()->flash('success', 'chat deleted');
            return response()->json([
                'data' => 1,
            ], 200);
        }
    }

    public function getSender(Request $request){
        if($request->ajax()){
            $user = User::find($request->id);
            return response()->json([
                'user' => $user
            ], 200);
        }
    }

}

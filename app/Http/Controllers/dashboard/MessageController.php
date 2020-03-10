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
        $chats = Chat::where('user1_id', auth()->user()->id)->orWhere('user2_id', auth()->user()->id)->get();
        return view('admin.chat.index')->with('chats', $chats);
    }

    public function getChatPage($id){
        $chat = Chat::find($id);
        return view('admin.chat.chat')->with('messages', $chat->messages)
                                        ->with('chat_id', $id)
                                        ->with('user_id', $chat->user2_id);
    }

    public function store(Request $request) {
        $chat = Chat::where('id', $request->chat_id)->first();
        if(!$chat){
            $chat = Chat::create([
                'user1_id' => auth()->user()->id, //sender
                'user2_id' => $request->user2_id //receiver
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

}

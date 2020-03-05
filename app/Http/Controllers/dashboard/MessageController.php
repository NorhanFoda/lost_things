<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Message;
use App\User;

class MessageController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('web');
    }

    public function getMessages(){
        $users = User::with('messages')->where('id', '!=', auth()->user()->id)->get();
        return view('admin.chat.index')->with('users', $users);
    }

    public function getChatPage($id){
        return view('admin.chat.chat')->with('messages', Message::where('chat_id', $id)->get())
                                                                    ->with('chat_id', $id)
                                                                    ->with('user_id', Message::where('chat_id', $id)->where('type', 0)
                                                                    ->pluck('user_id')->first());
    }

    public function store(Request $request) {

		$this->validate($request, [
			'content' => 'required',
			'chat_id' => 'required'
		]);

        //create sender
        $chat = Message::create([
            'message' => $request->content,
            'user_id' => auth()->user()->id, //sender
            'type' => 0,
            'chat_id' => $request->chat_id
        ]);

        //create receiver
        $chat = Message::create([
            'message' => $request->content,
            'user_id' => $request->user2_id, //reciever
            'type' => 1,
            'chat_id' => $request->chat_id
        ]);

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

}

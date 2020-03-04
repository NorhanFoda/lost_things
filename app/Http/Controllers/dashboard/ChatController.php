<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Message;
use App\User;

class ChatController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('web');
    }

    public function index(){
        return view('admin.chat.index');
    }

    public function store(Request $request) {
        // dd($request->all());
		$this->validate($request, [
			'content' => 'required',
			'id' => 'required'
		]);

        // $msg = Message::create($request->all());
        $chat = Message::create([
            'message' => $request->content,
            'user_id' => $request->id
        ]);
		if(User::find($request->id)->is_admin == 1){
            $chat->update(['type' => 1]);
        }
        else{
            $chat->update(['type' => 0]);
        }

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

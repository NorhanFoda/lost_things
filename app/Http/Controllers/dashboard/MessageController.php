<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Message;
use App\User;
use Illuminate\Support\Facades\Validator;

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
            'content' => 'required_without:image',
            'image' => 'required_without:content',
			'chat_id' => 'required'
        ]);
        
        if($request->image != null){
            // $imageRules = array(
            //     'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // );
            // //Validate image
            // $image_to_validate = array('image' => $request->image);
            // $imageValidator = Validator::make($image_to_validate, $imageRules);
            // if ($imageValidator->fails()) {
            //     return $imageValidator->messages();
            // }

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

        //create sender
        $chat = Message::create([
            'message' => $request->content,
            'user_id' => auth()->user()->id, //sender
            'image' => $image,
            'type' => 0,
            'chat_id' => $request->chat_id
        ]);

        //create receiver
        $chat = Message::create([
            'message' => $request->content,
            'user_id' => $request->user2_id, //reciever
            'image' => $image,
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

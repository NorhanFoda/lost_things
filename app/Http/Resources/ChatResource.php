<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Message;
use App\Http\Resources\MessageResourceCollection;
use App\Http\Resources\UserResourceForChat;
use App\User;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $messages = MessageResourceCollection::collection(Message::with('user')
                    ->where(['chat_id' => $this->id, 'user_id' => auth()->user()->id])->get());

    if($this->user1_id == auth()->user()->id){
        $user1 = User::find($this->user1_id);
        $user2 = User::find($this->user2_id);   
    }
    else if($this->user2_id == auth()->user()->id){
        $user1 = User::find($this->user2_id);
        $user2 = User::find($this->user1_id);   
    }

        return [
            'auth_user' => new UserResourceForChat($user1),
            'other_user' => new UserResourceForChat($user2),
            'chat_id' => $this->id,
            'messages' => $messages
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Message;
use App\Http\Resources\MessageResourceCollection;
use App\Http\Resources\UserResourceForChat;
use App\User;

class ChatResourceCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $messages = MessageResourceCollection::collection(Message::with('user')
                    ->where(['chat_id' => $this->id, 'user_id' => auth()->user()->id])->get());

        $last_message = new MessageResourceCollection($messages[count($messages) - 1]);
        $user1 = User::find($this->user1_id);
        $user2 = User::find($this->user2_id);

        return [
            'auth_user' => $this->user1_id == $user1->id ? new UserResourceForChat($user1) : new UserResourceForChat($user2),
            'other_user' => $this->user1_id == $user1->id ? new UserResourceForChat($user2) : new UserResourceForChat($user1),
            'chat_id' => $this->id,
            'last_message' => $last_message
        ];
    }
}

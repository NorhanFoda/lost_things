<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Message;
use App\Http\Resources\MessageResourceCollection;
use App\Http\Resources\UserResource;
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

        $user1 = User::find($this->user1_id);
        $user2 = User::find($this->user2_id);

        return [
            'auth_user' => $this->user1_id == $user1->id ? new UserResource($user1) : new UserResource($user2),
            'other_user' => $this->user1_id == $user1->id ? new UserResource($user2) : new UserResource($user1),
            'chat_id' => $this->id,
            'messages' => $messages
        ];
    }
}

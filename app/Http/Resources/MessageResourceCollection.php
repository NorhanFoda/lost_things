<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class MessageResourceCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'content' => $this->message,
            'type' => ($this->user_id == auth()->user()->id && $this->type == 0) ? 'auth sent' : 'auth received',
            'since' => $this->created_at->diffForHumans(\Carbon\Carbon::now())
        ];
    }
}

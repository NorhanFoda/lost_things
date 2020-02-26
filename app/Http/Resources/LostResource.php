<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class LostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'published_at' => $this->created_at,
            'published_sence' => $this->created_at->diffForHumans(Carbon::now()),
            'location' => $this->location,
            'category' => $this->category_id,
            'user' => $this->user_id,
            'href' => [
                'comments' => route('comments.index', $this->id)
            ]
        ];
    }
}

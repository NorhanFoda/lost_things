<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoundResource extends JsonResource
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
            'published_at' => $this->created_at,
            'location' => $this->location,
            'category' => $this->category_id,
            'user' => $this->user_id,
            'href' => [
                'comments' => route('comments.index', $this->id)
            ]
        ];
    }
}

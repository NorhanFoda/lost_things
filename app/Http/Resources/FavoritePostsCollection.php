<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class FavoritePostsCollection extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->images,
            'comments' => count($this->comments),
            'published_at' => $this->published_at,
            'published_sence' => $this->created_at->diffForHumans(Carbon::now()),
            'location' => $this->location,
            'found' => $this->found,
            'is_favorite' => 1,
            'show_details' => route('losts.show', $this->id),
            'category' => $this->category_id,
            'user' => $this->user_id,
            'href' => [
                'comments' => route('comments.index', $this->id)
            ]
        ];
    }
}

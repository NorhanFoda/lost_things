<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PostResourceCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $is_favorite = 0;
        $favorites = auth()->user()->favorites;
        if(count($favorites) > 0){
            foreach($favorites as $fav){
                if($fav->post_id == $this->id){
                    $is_favorite = 1;
                }
            }
        }
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->images,
            'comments' => count($this->comments),
            'published_at' => $this->published_at,
            'published_sence' => $this->created_at->diffForHumans(Carbon::now()),
            'location' => $this->location,
            'show_details' => route('losts.show', $this->id),
            'category' => $this->category_id,
            'is_favorite' => $is_favorite,
            'found' => $this->found,
            'user' => $this->user_id,
            'href' => [
                'comments' => route('comments.index', $this->id)
            ]
        ];
        
    }
}

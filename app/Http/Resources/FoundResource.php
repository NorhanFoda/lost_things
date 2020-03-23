<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
        $favorites = auth()->user()->favorites;
        $is_favorite = 0;
        if(count($favorites) > 0){
            foreach($favorites as $fav){
                if($fav->post_id == $this->id){
                    $is_favorite == 1;
                }
                else{
                    $is_favorite = 0;
                }
            }
        }
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'comments' => count($this->comments),
            'found' => $this->found,
            'is_favorite' => $is_favorite,
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

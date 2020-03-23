<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResourceCollection extends JsonResource
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
                if($fav->post_id == $this->post_id){
                    $is_favorite = 1;
                }
            }
        }
        return [
            'id' => $this->id,
            'text' => $this->text,
            'post_id' => $this->post_id,
            'is_favorite' => $is_favorite,
            'user' => $this->user,
            'post_ower' => auth()->user(),
            'published_at' => $this->created_at
        ];
    }
}

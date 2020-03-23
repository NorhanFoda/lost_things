<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Block;

class FoundResourceCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $blocked = Block::where('user_id', auth()->user()->id)->first();
        $is_favorite = 0;
        $favorites = auth()->user()->favorites;
        if(count($favorites) > 0){
            foreach($favorites as $fav){
                if($fav->post_id == $this->id){
                    $is_favorite = 1;
                }
            }
        }
        
        if($blocked != null){
            $is_blocked = $blocked->blocked_id;
            
            if($this->user_id != $is_blocked){
                return [
                    'id' => $this->id,
                    'title' => $this->title,
                    'description' => $this->description,
                    'category' => $this->category_id,
                    'is_favorite' => $is_favorite,
                    'comments' => count($this->comments),
                    'published_at' => $this->created_at,
                    'published_sence' => $this->created_at->diffForHumans(Carbon::now()),
                    'show_details' => route('founds.show', $this->id)
                ];
            }
        }
        else{
            return [
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->description,
                'category' => $this->category_id,
                'is_favorite' => $is_favorite,
                'comments' => count($this->comments),
                'published_at' => $this->created_at,
                'published_sence' => $this->created_at->diffForHumans(Carbon::now()),
                'show_details' => route('founds.show', $this->id)
            ];
        }
    }
}

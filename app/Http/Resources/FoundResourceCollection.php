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
        $is_blocked = Block::where('user_id', auth()->user()->id)->first()->blocked_id;
        if($is_blocked == null){   
            return [
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->description,
                'published_at' => $this->created_at,
                'published_sence' => $this->created_at->diffForHumans(Carbon::now()),
                'show_details' => route('founds.show', $this->id)
            ];
        }
        if($this->user_id != $is_blocked){
            return [
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->description,
                'published_at' => $this->created_at,
                'published_sence' => $this->created_at->diffForHumans(Carbon::now()),
                'show_details' => route('founds.show', $this->id)
            ];
        }
    }
}

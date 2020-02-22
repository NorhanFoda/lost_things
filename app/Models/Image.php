<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Image extends Model
{
    protected $fillable = [
        'path', 'lost_id'
    ];

    public function post(){
        return $this->belongsTo(Post::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Comment extends Model
{
    protected $fillable = ['text', 'post_id', 'user_id'];

    public function post(){
        return $this->belongsTo(Post::class);
    }
}

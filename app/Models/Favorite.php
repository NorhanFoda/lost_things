<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Post;

class Favorite extends Model
{
    protected $fillable = ['post_id'];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
    
    public function posts(){
        return $this->hasMany(Post::class);
    }
}

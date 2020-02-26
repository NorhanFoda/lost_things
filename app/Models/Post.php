<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Comment;
use App\Models\Image;

class Post extends Model
{
    protected $fillable = [ 'title', 'description', 'location', 'place',
    'found', 'category_id', 'user_id', 'published_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function images(){
        return $this->hasMany(Image::class);
    }
}

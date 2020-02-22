<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Favorite extends Model
{
    protected $fillable = ['post_id'];

    public function products()
    {
        return $this->belongsToMany(User::class);
    }
}

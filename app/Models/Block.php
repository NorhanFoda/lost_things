<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Block extends Model
{
    protected $fillable = ['user_id', 'blocked_id'];

    public function blockingUser(){
        return $this->belongsTo(User::class);
    }
}

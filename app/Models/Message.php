<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Firebase\SyncsWithFirebase;
use App\User;
use App\Models\Chat;

class Message extends Model
{
    use SyncsWithFirebase;
    protected $fillable = ['message', 'user_id','type', 'chat_id','image'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function chat(){
        return $this->belongsTo(Chat::class);
    }
}

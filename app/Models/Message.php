<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Firebase\SyncsWithFirebase;
use App\User;

class Message extends Model
{
    use SyncsWithFirebase;
    protected $fillable = ['message', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

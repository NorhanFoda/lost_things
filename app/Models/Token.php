<?php

namespace App\Models;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = ['user_id', 'token'];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}

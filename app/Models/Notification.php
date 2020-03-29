<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
// use Mpociot\Firebase\SyncsWithFirebase;

class Notification extends Model
{
    // use SyncsWithFirebase;
    protected $fillable = [
        'msg_ar', 'user_id', 'read'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Post;
use App\Models\Favorite;
use App\Models\Block;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Chat;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'code',
        'birth_date', 'image', 'is_blocked', 
        'is_admin', 'is_verified', 'location_active', 
        'notification_active', 'lang', 'phone', 'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // Rest omitted for brevity

    public function routeNotificationForFcm()
    {
        // return $this->device_token;
        return $this->fcm_token;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Favorite::class);
    }

    public function blockList(){
        return $this->hasMany(Block::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function chats(){
        return $this->hasMany(Chat::class, 'user1_id', 'id');
    }
}

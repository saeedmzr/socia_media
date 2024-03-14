<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);

    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);

    }

    public function starter_conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, "user1_id");
    }

    public function receiver_conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, "user2_id");
    }

    public function send_messages(): HasMany
    {
        return $this->hasMany(Message::class, "sender_user_id");
    }

    public function receive_messages(): HasMany
    {
        return $this->hasMany(Message::class, "receiver_user_id");
    }

    public function starter_friendships(): HasMany
    {
        return $this->hasMany(Friendship::class, "user1_id");
    }

    public function receiver_friendships(): HasMany
    {
        return $this->hasMany(Friendship::class, "user2_id");
    }


    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

}

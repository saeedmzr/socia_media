<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ["id"];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, "sender_user_id");
    }


    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, "receiver_user_id");
    }
}

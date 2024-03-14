<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friendship extends Model
{
    use HasFactory;

    protected $guarded = ["id"];


    public function starter(): BelongsTo
    {
        return $this->belongsTo(User::class, "user1_id");
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, "user2_id");
    }

}

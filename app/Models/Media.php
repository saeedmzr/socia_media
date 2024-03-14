<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;

    protected $guarded = ["id"];


    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}

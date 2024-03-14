<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ["id"];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }


    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function scopeOwner($query, $userId = null)
    {
        if (!$userId) {
            $userId = request()->user()->id;
        }
        return $this->where('user_id', $userId);
    }

    public function scopeFilters(Builder $builder, array $filters = []): Builder
    {
        foreach ($filters as $key => $filter) {
            $builder->where($key, $filter);
        }
        return $builder;
    }

}

<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;


class CommentRepository extends BaseRepository
{
    protected Model $model;

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }
}

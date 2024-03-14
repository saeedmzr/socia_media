<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;


class CommentRepository extends BaseRepository
{
    protected Model $model;
    private $queryBuilder;

    public function __construct(Comment $model)
    {
        $this->model = $model;
        $this->queryBuilder = $this->model->newQuery();
    }

    public function owned($postId = null)
    {
        $this->queryBuilder = $this->model::PostOwner($postId);
        return $this;
    }
}

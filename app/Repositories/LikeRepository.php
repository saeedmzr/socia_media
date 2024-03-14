<?php

namespace App\Repositories;

use App\Models\Like;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Termwind\Components\Li;


class LikeRepository extends BaseRepository
{
    protected Model $model;

    public function __construct(Like $model)
    {
        $this->model = $model;
    }
}

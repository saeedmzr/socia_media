<?php

namespace App\Repositories;

use App\Models\Friendship;
use App\Models\Like;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Termwind\Components\Li;


class FriendshipRepository extends BaseRepository
{
    protected Model $model;

    public function __construct(Friendship $model)
    {
        $this->model = $model;
    }
}

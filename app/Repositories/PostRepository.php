<?php

namespace App\Repositories;

use App\Enums\PostVisibilityEnum;
use App\Events\UpdateTaskEvent;
use App\Models\Post;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PostRepository extends BaseRepository
{
    protected Model $model;
    private $queryBuilder;

    public function __construct(Post $model)
    {
        $this->model = $model;
        $this->queryBuilder = $this->model->newQuery();
    }

    public function owned($userId = null)
    {
        $this->queryBuilder = $this->model::Owner($userId);
        return $this;
    }

    public function filtersAndPaginate(array $filters = [], int $size = 10)
    {
        return $this->queryBuilder->filters($filters)->paginate($size);
    }

}

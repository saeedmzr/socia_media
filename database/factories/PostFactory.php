<?php

namespace Database\Factories;

use App\Enums\PostVisibilityEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Media;
use App\Models\Post;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use PhpParser\Comment;

class PostFactory extends Factory
{
    /**
     * The name of the model associated with the factory.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'content' => $this->faker->randomHtml(),
            'visibility' => PostVisibilityEnum::random(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

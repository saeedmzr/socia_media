<?php

namespace Database\Factories;

use App\Enums\PostVisibilityEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the model associated with the factory.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
            'body' => $this->faker->text(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

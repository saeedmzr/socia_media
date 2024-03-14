<?php

namespace Database\Factories;

use App\Enums\MediaTypeEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Media;
use App\Models\Post;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        $faker = FakerFactory::create();

        $mediaType = MediaTypeEnum::random();
        $extension = match ($mediaType) {
            MediaTypeEnum::PICTURE => $faker->randomElement(['jpg', 'png', 'gif']),
            MediaTypeEnum::VIDEO => $faker->randomElement(['mp4', 'mov']),
        };

        $filePath = "posts/{$faker->uuid()}.{$extension}"; // Replace with actual storage path generation logic

        return [
            'post_id' => Post::factory(),
            'file_path' => $filePath,
            'media_type' => $mediaType,
            'created_at' => $faker->dateTimeThisMonth(),
            'updated_at' => null,
        ];
    }

}

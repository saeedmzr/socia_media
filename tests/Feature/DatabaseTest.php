<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->postRepository = new PostRepository(new Post());
    }

    /** @test */
    public function database_up_and_running()
    {
        $createdUser = User::factory()->create();
        $createdPost = Post::factory()->create();

        $this->assertDatabaseHas('users', [
            'email' => $createdUser->email
        ]);
        $this->assertDatabaseHas('posts', [
            'id' => $createdPost->id,
            'content' => $createdPost->content,
        ]);
    }
}

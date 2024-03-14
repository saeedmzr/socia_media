<?php

namespace Tests\Unit;

use App\Enums\PostStatusEnum;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected PostRepository $postRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->postRepository = new PostRepository(new Post());
    }

    /** @test */
    public function it_can_create_a_post()
    {
        $createdUser = User::factory()->create();
        $postData = [
            'content' => 'Test Post',
            'user_id' => $createdUser->id
        ];

        $createdPost = $this->postRepository->create($postData);

        $this->assertInstanceOf(Post::class, $createdPost);
        $this->assertEquals($postData['content'], $createdPost->content);
        // Add assertions for other fields as well
    }

    /** @test */
    public function it_can_find_a_post_by_id()
    {
        $post = Post::factory()->create();

        $foundPost = $this->postRepository->findById($post->id);

        $this->assertInstanceOf(Post::class, $foundPost);
        $this->assertEquals($post->id, $foundPost->id);
    }

    /** @test */
    public function it_can_update_a_post()
    {
        $post = Post::factory()->create();
        $updatedData = [
            'content' => 'Updated Post Content',
        ];

        $this->postRepository->update($post->id, $updatedData);

        $updatedPost = Post::find($post->id);

        $this->assertEquals($updatedData['content'], $updatedPost->content);
    }

    /** @test */
    public function it_can_delete_a_post()
    {
        $post = Post::factory()->create();

        $this->assertTrue($this->postRepository->deleteById($post->id));
        $this->assertNull(Post::find($post->id));
    }
}

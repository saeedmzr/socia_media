<?php

namespace Tests\Feature;

use App\Enums\PostStatusEnum;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->postRepository = new PostRepository(new Post());
    }

    /** @test */
    public function user_can_create_post()
    {
        $this->actingAs($this->user);

        $data = [
            'content' => 'Test Post',
        ];

        $response = $this->postJson('/api/posts', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'content',
                ],
            ]);

        $this->assertDatabaseHas('posts', [
            'content' => $data['content'],
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_can_see_posts()
    {
        $this->actingAs($this->user);

        Post::factory(5)->create();

        $response = $this->getJson('/api/posts');

        $response->assertOk()
            ->assertJsonCount(5, 'data.items');
    }


    /** @test */
    public function user_can_update_post()
    {
        $this->actingAs($this->user);

        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $updatedData = [
            'content' => 'Updated Post content',
        ];
        $response = $this->putJson(route('posts.update', $post->id), $updatedData);

        $response->assertStatus(201)
            ->assertJson(['data' => [
                'content' => 'Updated Post content',
            ]]);

        $this->assertDatabaseHas('posts', $updatedData);
    }

    /** @test */
    public function user_can_find_a_post()
    {
        $this->actingAs($this->user);

        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('posts.show', $post->id));

        $response->assertOk()
            ->assertJson(['data' => [
                'id' => $post->id
            ]]);
    }

    /** @test */
    public function user_can_delete_post()
    {
        $this->actingAs($this->user);

        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson(route('posts.destroy', $post->id));

        $response->assertStatus(201);

        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    /** @test */
    public function test_pagination_posts()
    {
        $this->actingAs($this->user);

        $response = $this->getJson(route('posts.index'));

        $response->assertOk()
            ->assertJsonStructure(
                [
                    'data' =>
                        [
                            "items",
                            "pagination" =>
                                [
                                    "current_page", "total_pages", "per_page", "links"
                                ]

                        ]
                ]
            );
    }


}

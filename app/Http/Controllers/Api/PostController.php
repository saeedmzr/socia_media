<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Repositories\MediaRepository;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(name="Post Management")
 */
class PostController extends BaseController
{


    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;
    private MediaRepository $mediaRepository;

    /**
     * PostController constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository, MediaRepository $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * @OA\Get(
     *     path="/posts",
     *     summary="Get a paginated list of posts",
     *                    tags={"Post Management"},
     *     description="Retrieves a list of posts owned by the authenticated user. Use query parameters for filtering and pagination.",
     *     @OA\Parameter(
     *         name="filters",
     *         in="query",
     *         description="Optional filters for searching posts (refer to your specific implementation)",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *                 enum={"completed", "create", "system_completed"},
     *                 description="Filter posts by their status"
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="size",
     *         in="query",
     *         description="Number of items per page (default 10)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/PostSchema")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *      security={{"sanctumAuth": {}}}
     * )
     */

    public function index(Request $request): JsonResponse
    {
        $posts = $this->postRepository->owned($request->user()->id)->filtersAndPaginate($request->get('filters', []), $request->get('size', 10));

        return $this->successResponse([
            "items" => PostResource::collection($posts),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'total_pages' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'links' => $posts->links(),
            ],
        ], "Post list has been fetched successfully.");
    }

    /**
     * @OA\Get(
     *     path="/posts/{postId}",
     *     summary="Get a post by ID",
     *     description="Retrieves a single post identified by its ID.",
     *     tags={"Post Management"},
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PostSchema"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
     */
    public function show(Request $request, int $postId): JsonResponse
    {

        $post = $this->postRepository->owned($request->user()->id)->findById($postId);
        if ($post)
            return $this->successResponse(
                new PostResource($post),
                "Post has been fetched successfully."
            );
        return $this->errorResponse("Post Not Found.", 404);

    }

    /**
     * @OA\Post(
     *     path="/posts",
     *     summary="Create a new post",
     *     description="Creates a new post with the provided details.",
     *               tags={"Post Management"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreatePostSchema")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/PostSchema"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
     */
    public function store(CreatePostRequest $request): JsonResponse
    {

        $post = $this->postRepository->create(
            collect($request->validated())->except(["media"])->toArray()
        );
        $this->mediaRepository->upload($post->id, $request->file("media"));

        return $this->successResponse(
            new PostResource($post),
            "Post has been created successfully.",
            201
        );
    }

    /**
     * @OA\Put(
     *     path="/posts/{postId}",
     *     summary="Update a post",
     *     description="Updates an existing post with the provided details.",
     *               tags={"Post Management"},
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID of the post to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdatePostSchema")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/PostSchema"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden: Could not update this post"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
     */
    public function update(UpdatePostRequest $request, int $postId): JsonResponse
    {
        try {
            $this->postRepository->owned($request->user()->id)->update(
                $postId,
                collect($request->validated())->except(["media"])->toArray()
            );

            $post = $this->postRepository->owned($request->user()->id)->findById($postId);
            if ($request->hasFile("media") !== null)
                $this->mediaRepository->upload($post->id, $request->file("media"));

            return $this->successResponse(
                new PostResource($post),
                "Post has been updated successfully.",
                201
            );
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse("Could not update this post.", 403);
        }

    }

    /**
     * @OA\Delete(
     *     path="/posts/{postId}",
     *     summary="Delete a post",
     *     description="Deletes a post with the provided ID.",
     *               tags={"Post Management"},
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID of the post to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
     */
    public function destroy(Request $request, int $postId): JsonResponse
    {
        $this->postRepository->owned($request->user()->id)->deleteById($postId);
        return $this->successResponse([], "Post has been deleted successfully.", 201);
    }
}

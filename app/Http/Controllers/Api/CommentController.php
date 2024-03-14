<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Repositories\PostRepository;
use App\Repositories\CommentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(name="Comment Management")
 */
class CommentController extends BaseController
{


    /**
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * CommentController constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @OA\Get(
     *     path="/posts/comments/{commentId}",
     *     summary="Get a comment by ID",
     *     description="Retrieves a single comment identified by its ID.",
     *     tags={"Comment Management"},
     *     @OA\Parameter(
     *         name="commentId",
     *         in="path",
     *         description="ID of the comment",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/CommentSchema"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
     */
    public function show(int $commentId): JsonResponse
    {

        $comment = $this->commentRepository->findById($commentId);
        if ($comment)
            return $this->successResponse(
                new CommentResource($comment),
                "Comment has been fetched successfully."
            );
        return $this->errorResponse("Comment Not Found.", 404);

    }

    /**
     * @OA\Put(
     *     path="/posts/comments",
     *     summary="Create a comment",
     *     description="Create a comment with the provided details.",
     *               tags={"Comment Management"},

     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateCommentSchema")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/CommentSchema"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden: Could not update this comment"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
     */
    public function store(CreateCommentRequest $request): JsonResponse
    {

        $comment = $this->commentRepository->create($request->validated());

        return $this->successResponse(
            new CommentResource($comment),
            "Comment has been created successfully.",
            201
        );
    }

    /**
     * @OA\Put(
     *     path="/posts/comments/{commentId}",
     *     summary="Update a comment",
     *     description="Updates an existing comment with the provided details.",
     *               tags={"Comment Management"},
     *     @OA\Parameter(
     *         name="commentId",
     *         in="path",
     *         description="ID of the comment to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCommentSchema")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/CommentSchema"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden: Could not update this comment"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
     */
    public function update(UpdateCommentRequest $request, int $commentId): JsonResponse
    {
        try {
            $this->commentRepository->owned($request->user()->id)->update($commentId, $request->validated());

            $comment = $this->commentRepository->owned($request->user()->id)->findById($commentId);

            return $this->successResponse(
                new CommentResource($comment),
                "Comment has been updated successfully.",
                201
            );
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse("Could not update this comment.", 403);
        }

    }

    /**
     * @OA\Delete(
     *     path="/posts/comments/{commentId}",
     *     summary="Delete a comment",
     *     description="Deletes a comment with the provided ID.",
     *               tags={"Comment Management"},
     *     @OA\Parameter(
     *         name="commentId",
     *         in="path",
     *         description="ID of the comment to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     *      security={{"sanctumAuth": {}}}
     */
    public function destroy(Request $request, int $commentId): JsonResponse
    {
        $this->commentRepository->owned($request->user()->id)->deleteById($commentId);
        return $this->successResponse([], "Comment has been deleted successfully.", 201);
    }
}

<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateCommentSchema",
 *     @OA\Property(
 * *         property="body",
 * *         type="string",
 * *         description="comment's body"
 * *     ),
 * )
 */
class CreateCommentRequest extends FormRequest
{

    public function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => '',
            'body' => ["required", "string"],

        ];
    }
}

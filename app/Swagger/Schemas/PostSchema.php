<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     required={"id", "title", "description", "user_id", "created_at"},
 *     @OA\Property(property="id", type="integer", example=101),
 *     @OA\Property(property="title", type="string", example="My First Post"),
 *     @OA\Property(property="description", type="string", example="This is the content of the post."),
 *     @OA\Property(property="user_id", type="integer", example=1, description="ID of the post author"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-11T09:00:00Z")
 * )
 */

class PostSchema {}

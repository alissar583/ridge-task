<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="UserWithPosts",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/User"),
 *         @OA\Schema(
 *             type="object",
 *             @OA\Property(
 *                 property="posts",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Post")
 *             )
 *         )
 *     }
 * )
 */

class UserWithPostsSchema {}

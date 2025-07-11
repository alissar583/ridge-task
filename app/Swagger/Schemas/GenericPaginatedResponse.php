<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="GenericPaginatedResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Data fetched successfully"),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(oneOf={
 *             @OA\Schema(ref="#/components/schemas/User"),
 *             @OA\Schema(ref="#/components/schemas/Post"),
 *             @OA\Schema(ref="#/components/schemas/Notification")
 *         })
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="total", type="integer", example=100)
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string", example="http://localhost/api/items?page=1"),
 *         @OA\Property(property="last", type="string", example="http://localhost/api/items?page=10"),
 *         @OA\Property(property="prev", type="string", nullable=true, example=null),
 *         @OA\Property(property="next", type="string", nullable=true, example=null)
 *     )
 * )
 */
class GenericPaginatedResponse {}

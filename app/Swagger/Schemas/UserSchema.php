<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     required={"id", "name", "email" , "role"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 * @OA\Property(
 *     property="role",
 *     type="string",
 *     enum={"admin", "editor", "user"},
 *     example="user",
 *     description="Role of the user"
 * ),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com")
 * )
 */


class UserSchema {}

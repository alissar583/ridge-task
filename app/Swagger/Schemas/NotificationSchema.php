<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Notification",
 *     type="object",
 *     required={"id", "title", "message","created_at"},
 *     @OA\Property(property="id", type="integer", example=1001),
 *     @OA\Property(property="title", type="string", example="New message received"),
 *     @OA\Property(property="message", type="string", example="You have a new message from Alice."),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-11T12:34:56Z")
 * )
 */
class NotificationSchema {}

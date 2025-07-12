<?php

namespace Modules\Notification\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Notification\DTOs\CreateNotificationDto;
use Modules\Notification\Http\Requests\NotificationListRequest;
use Modules\Notification\Http\Requests\StoreNotificationRequest;
use Modules\Notification\Models\Notification;
use Modules\Notification\Services\NotificationService;
use Modules\Notification\Transformers\NotificationResource;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notificationService) {}

    /**
     * @OA\Get(
     *     path="/api/notifications",
     *     summary="Get a paginated list of notifications",
     *     description="Returns a paginated list of notifications. Supports optional pagination.",
     *     tags={"Notifications"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of notifications per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10, default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of notifications",
     *         @OA\JsonContent(ref="#/components/schemas/GenericPaginatedResponse")
     *     )
     * )
     */
    public function index(NotificationListRequest $request)
    {
        $perPage = $request->input('per_page', 15);

        $posts = $this->notificationService->getNotifications($perPage);
        $posts = NotificationResource::collection($posts)->response()->getData();

        return $this->successResponse($posts, "Paginated list of notifications");
    }

    /**
     * @OA\Post(
     *     path="/api/notifications",
     *     summary="Create a new notification",
     *      description="Creates a new notification. Only authenticated users are allowed to access this endpoint.",

     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="My first notification"),
     *             @OA\Property(property="body", type="string", nullable=true, maxLength=10000, example="This is a body of the notification.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Notification created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="notification created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Notification"),
     *             @OA\Property(property="meta", type="string", nullable=true, example=null),
     *             @OA\Property(property="links", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="The title field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function store(StoreNotificationRequest $request)
    {
        $dto = CreateNotificationDto::fromArray(
            $request->validated(),
        );

        $notification = $this->notificationService->store($dto);
        $notification = NotificationResource::make($notification);

        return $this->successResponse($notification);
    }

    /**
     * @OA\Delete(
     *     path="/api/notifications/{id}",
     *     summary="Delete a specific notification",
     *     description="Deletes a notification by its ID.",
     *     operationId="deleteNotification",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the notification to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="notification deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="notification deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Notification not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     )
     * )
     */

    public function destroy(Notification $notification)
    {
        $this->notificationService->destroy($notification);
        return $this->successResponse(message: "Notification deleted successfully");
    }
}

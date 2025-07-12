<?php

namespace Modules\Notification\Services;

use Modules\Notification\DTOs\CreateNotificationDto;
use Modules\Notification\DTOs\NotificationDto;
use Modules\Notification\Events\NotificationCreated;
use Modules\Notification\Jobs\SendNotificationJob;
use Modules\Notification\Models\Notification;
use Modules\Notification\Repositories\Contracts\NotificationRepositoryInterface;

class NotificationService
{
    public function __construct(protected NotificationRepositoryInterface $notificationRepository) {}

    public function getNotifications(int $perPage)
    {
        return $this->notificationRepository->getNotificationsPaginated($perPage);
    }


    public function store(CreateNotificationDto $data): Notification
    {
        $notification = $this->notificationRepository->create($data);
        $notificationDto = new NotificationDto(
            $notification->id,
            $notification->title,
            $notification->body,
            $notification->created_at
        );
        SendNotificationJob::dispatch($notificationDto);
        broadcast(new NotificationCreated($notificationDto))->toOthers();

        return $notification;
    }

    public function destroy(Notification $post)
    {
        $this->notificationRepository->delete($post);
    }
}

<?php

namespace Modules\Notification\Services;

use Modules\Notification\DTOs\NotificationDto;
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


    public function store(NotificationDto $data): Notification
    {
        $notification = $this->notificationRepository->create($data);
        SendNotificationJob::dispatch($data);
        return $notification;
    }

    public function destroy(Notification $post)
    {
        $this->notificationRepository->delete($post);
    }
}

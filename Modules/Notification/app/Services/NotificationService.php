<?php

namespace Modules\Notification\Services;

use Modules\Notification\DTOs\CreateNotificationDto;
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
        return $this->notificationRepository->create($data);
    }

    public function destroy(Notification $post)
    {
        $this->notificationRepository->delete($post);
    }
}

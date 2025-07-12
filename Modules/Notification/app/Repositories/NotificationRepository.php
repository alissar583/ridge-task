<?php

namespace Modules\Notification\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Notification\DTOs\CreateNotificationDto;
use Modules\Notification\Models\Notification;
use Modules\Notification\Repositories\Contracts\NotificationRepositoryInterface;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function create(CreateNotificationDto $data): Notification
    {
        return Notification::create($data->toArray());
    }

    public function getNotificationsPaginated(int $perPage): LengthAwarePaginator
    {
        return Notification::query()->paginate($perPage);
    }

    public function delete(Notification $notification)
    {
        $notification->delete();
    }
}

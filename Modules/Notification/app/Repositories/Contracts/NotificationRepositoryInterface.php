<?php

namespace Modules\Notification\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Notification\DTOs\CreateNotificationDto;
use Modules\Notification\Models\Notification;

interface NotificationRepositoryInterface
{
    public function getNotificationsPaginated(int $perPage): LengthAwarePaginator;
    public function delete(Notification $notification);
    public function create(CreateNotificationDto $data) : Notification;
}

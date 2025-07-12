<?php

namespace Modules\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\Notification\DTOs\NotificationDto;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $notificationData;

    public function __construct(NotificationDto $notificationData)
    {
        $this->notificationData = $notificationData;
    }

    public function handle(): void
    {

        Log::info('Simulated sending notification:', [
            'title' => $this->notificationData->title,
            'body' => $this->notificationData->body,
        ]);
    }
}

<?php

namespace Modules\Notification\DTOs;

class NotificationDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $body,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            body: $data['body'],
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
        ];
    }
}

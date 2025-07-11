<?php

namespace Modules\Post\DTOs;

class CreatePostDto
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly int $user_id
    ) {}

    public static function fromArray(array $data, int $user_id): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
            user_id: $user_id
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => $this->user_id,
        ];
    }
}

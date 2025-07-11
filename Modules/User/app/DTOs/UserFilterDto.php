<?php

namespace Modules\User\DTOs;

class UserFilterDto
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $email = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null
        );
    }
}

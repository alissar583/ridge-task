<?php

namespace Modules\User\DTOs;

class LoginDto
{
    public function __construct(
        public readonly string $role,
        public readonly string $email
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            role: $data['role'],
            email: $data['email']
        );
    }
}

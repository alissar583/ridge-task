<?php

namespace Modules\User\DTOs;

class UserFilterDto
{
    public ?string $name;
    public ?string $email;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
    }
}

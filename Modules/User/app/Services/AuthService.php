<?php

namespace Modules\User\Services;

use Modules\User\DTOs\LoginDto;
use Modules\User\DTOs\UserDto;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;

class AuthService
{
    public function __construct(protected UserRepositoryInterface $userRepository) {}

    public function login(LoginDto $loginDto)
    {
        $userDto = new UserDto('MockUser', $loginDto->email, $loginDto->role, 'password');
        $user = $this->userRepository->firstOrCreate($userDto);
        $user['token'] = $user->createToken('mock-token')->plainTextToken;

        return $user;
    }
}

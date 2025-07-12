<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\User\DTOs\LoginDto;
use Modules\User\DTOs\UserDto;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;

class AuthService
{
    public function __construct(protected UserRepositoryInterface $userRepository) {}

    public function login(LoginDto $loginDto)
    {

        $user = $this->userRepository->findByEmail($loginDto->email);

        if ($user) {
            if ($user->role->value !== $loginDto->role) {
                throw ValidationException::withMessages([
                    'email' => ['This email is already used with a different role. Please use another email.']
                ]);
            }
        } else {
            $userDto = new UserDto('MockUser', $loginDto->email, $loginDto->role, 'password');
            $user = $this->userRepository->create($userDto);
        }


        $user['token'] = $user->createToken('mock-token')->plainTextToken;

        return $user;
    }
}

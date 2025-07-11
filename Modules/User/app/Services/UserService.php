<?php

namespace Modules\User\Services;

use Modules\User\DTOs\UserFilterDto;
use Modules\User\Models\User;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;

class UserService
{

    public function __construct(protected UserRepositoryInterface $userRepository) {}

    public function getUsers(array $filters, int $perPage = 15)
    {
        $filterData = new UserFilterDto($filters);

        return $this->userRepository->getUsersPaginated($filterData, $perPage);
    }

    public function show(User $user): User
    {
        return $this->userRepository->show($user);
    }
}

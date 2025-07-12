<?php

namespace Modules\User\Repositories\Contracts;

use Modules\User\DTOs\UserFilterDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\User\DTOs\UserDto;
use Modules\User\Models\User;

interface UserRepositoryInterface
{
    public function getUsersPaginated(UserFilterDto $filterData, int $perPage): LengthAwarePaginator;
    public function show(User $user): User;
    public function create(UserDto $user): User;
    public function findByEmail(string $email): ?User;
}

<?php

namespace Modules\User\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\User\DTOs\UserDto;
use Modules\User\DTOs\UserFilterDto;
use Modules\User\Models\User;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getUsersPaginated(UserFilterDto $filterData, int $perPage): LengthAwarePaginator
    {
        $name = $filterData->name;
        $email = $filterData->email;

        $query = User::query()
            ->when($name, function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($email, function ($query) use ($email) {
                $query->where('email', 'like', '%' . $email . '%');
            });

        return $query->paginate($perPage);
    }

    public function show(User $user): User
    {
        return $user->load('posts');
    }

    public function firstOrCreate(UserDto $user): User
    {
        return User::firstOrCreate(
            ['email' => $user->email],
            [
                'name' => $user->name,
                'role' => $user->role,
                'password' => $user->password
            ]
        );
    }
}

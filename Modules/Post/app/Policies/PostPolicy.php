<?php

namespace Modules\Post\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Post\Models\Post;
use Modules\User\Enums\UserRole;
use Modules\User\Models\User;

class PostPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Post $post): bool
    {
        return $user->role === UserRole::Admin || $user->id === $post->user_id;
    }
}

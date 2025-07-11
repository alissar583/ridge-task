<?php

namespace Modules\User\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Post\DTOs\CreatePostDto;
use Modules\Post\Models\Post;
use Modules\User\Models\User;

interface PostRepositoryInterface
{
    public function getPostsPaginated(int $perPage): LengthAwarePaginator;
    public function delete(Post $post);
    public function create(CreatePostDto $data) : Post;
}

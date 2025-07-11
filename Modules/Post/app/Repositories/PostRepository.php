<?php

namespace Modules\Post\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Post\DTOs\CreatePostDto;
use Modules\Post\Models\Post;
use Modules\User\Repositories\Contracts\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    public function create(CreatePostDto $data): Post
    {
        return Post::create($data->toArray());
    }

    public function getPostsPaginated(int $perPage): LengthAwarePaginator
    {
        return Post::query()->paginate($perPage);
    }

    public function delete(Post $post)
    {
        $post->delete();
    }
}

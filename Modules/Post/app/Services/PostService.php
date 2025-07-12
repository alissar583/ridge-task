<?php

namespace Modules\Post\Services;

use Modules\Post\DTOs\CreatePostDto;
use Modules\Post\Models\Post;
use Modules\User\Repositories\Contracts\PostRepositoryInterface;

class PostService
{
    public function __construct(protected PostRepositoryInterface $postRepository) {}

    public function getPosts(int $perPage)
    {
        return $this->postRepository->getPostsPaginated($perPage);
    }

    public function store(CreatePostDto $data): Post
    {
        return $this->postRepository->create($data);
    }

    public function destroy(Post $post)
    {
        $this->postRepository->delete($post);
    }
}

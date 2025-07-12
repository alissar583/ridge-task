<?php

namespace Tests\Unit\Modules\Post\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;
use Modules\Post\DTOs\CreatePostDto;
use Modules\Post\Models\Post;
use Modules\Post\Services\PostService;
use Modules\Post\Repositories\Contracts\PostRepositoryInterface;

class PostServiceTest extends TestCase
{
    protected $mockRepo;
    protected PostService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockRepo = Mockery::mock(PostRepositoryInterface::class);

        $this->service = new PostService($this->mockRepo);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_posts_returns_paginated_data()
    {
        $paginator = Mockery::mock(LengthAwarePaginator::class);
        $this->mockRepo
            ->shouldReceive('getPostsPaginated')
            ->once()
            ->with(10)
            ->andReturn($paginator);

        $result = $this->service->getPosts(10);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_store_creates_post()
    {
        $dto = new CreatePostDto(
            title: 'Sample Title',
            description: 'Description here',
            user_id: 1
        );

        $post = new Post($dto->toArray());

        $this->mockRepo
            ->shouldReceive('create')
            ->once()
            ->with($dto)
            ->andReturn($post);

        $result = $this->service->store($dto);

        $this->assertInstanceOf(Post::class, $result);
        $this->assertEquals('Sample Title', $result->title);
    }

    public function test_destroy_deletes_post()
    {
        $post = new Post(['id' => 1]);

        $this->mockRepo
            ->shouldReceive('delete')
            ->once()
            ->with($post);

        $this->service->destroy($post);

        $this->assertTrue(true);
    }
}

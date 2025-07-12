<?php

namespace Tests\Feature\Modules\User;

use Tests\TestCase;
use Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_users_returns_paginated_list()
    {
        User::factory()->count(30)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => ['id', 'name', 'email', 'role']
            ],
            'meta',
            'links',
        ]);

        $this->assertCount(15, $response->json('data'));
    }
}

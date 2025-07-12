<?php

namespace Tests\Feature\Modules\Post;

use Tests\TestCase;
use Modules\Post\Models\Post;
use Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Enums\UserRole;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => UserRole::Editor->value,
        ]);
    }

    public function test_unauthenticated_cannot_create_post()
    {
        $response = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(401); // Unauthorized
    }

    public function test_user_without_proper_role_cannot_create_post()
    {
        $normalUser = User::factory()->create(['role' => UserRole::User->value]);

        $response = $this->actingAs($normalUser, 'sanctum')->postJson('/api/posts', [
            'title' => 'Test Post',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_create_post_with_valid_data()
    {
        $user = \Modules\User\Models\User::factory()->create([
            'role' => \Modules\User\Enums\UserRole::Editor->value,
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'title',
                'description',
                'created_at',
            ],
        ]);

        $this->assertDatabaseHas('p_posts', [
            'title' => 'Test Post',
            'user_id' => $user->id,
        ]);
    }

    public function test_validation_errors_for_missing_title()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/posts', [
                'description' => 'No title here',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }
}

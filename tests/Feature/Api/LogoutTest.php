<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ResponseStructure;
use Tests\Traits\WithUser;

class LogoutTest extends TestCase
{
    use RefreshDatabase, WithUser, ResponseStructure;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupUser();
    }

    public function test_user_can_logout()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/logout');

        $response->assertOk()
            ->assertJsonStructure(['token' => [...$this->tokenStructure]])
            ->assertJson([
                'token' => [
                    'status' => 'Revoked'
                ]
            ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $this->user->id
        ]);
    }

    public function test_unauthorized_user_cannot_logout()
    {
        $response = $this->postJson('/api/logout');

        $response->assertUnauthorized()
            ->assertJsonStructure($this->httpApiExceptionStructure);
    }
}

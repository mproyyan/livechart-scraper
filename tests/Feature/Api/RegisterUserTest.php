<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\WithUser;

class RegisterUserTest extends TestCase
{
    use WithUser, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupUser();
    }

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Royyan',
            'email' => 'royyan@roy.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertCreated()
            ->assertExactJson([
                'user' => [
                    'name' => 'Royyan',
                    'email' => 'royyan@roy.com',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Royyan',
            'email' => 'royyan@roy.com',
        ]);
    }
}

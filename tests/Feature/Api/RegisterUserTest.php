<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ResponseStructure;
use Tests\Traits\WithUser;

class RegisterUserTest extends TestCase
{
    use WithUser, RefreshDatabase, ResponseStructure;

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

    public function test_user_cannot_register_because_selected_email_already_used()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Royyan',
            'email' => $this->user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertUnprocessable()
            ->assertJsonStructure($this->httpApiExceptionStructure);
    }

    public function test_user_cannot_register_because_many_problem_on_field()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Royyan',
            'email' => $this->user->email,
            'password' => 'password',
            'password_confirmation' => 'password not matching'
        ]);

        $response->assertUnprocessable()
            ->assertJsonStructure([...$this->httpApiExceptionStructure, 'problems']);
    }
}

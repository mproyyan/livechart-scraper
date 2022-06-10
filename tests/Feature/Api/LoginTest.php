<?php

namespace Tests\Feature\Api;

use App\Http\Requests\LoginRequest;
use Database\Factories\UserFactory;
use Illuminate\Cache\RateLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ResponseStructure;
use Tests\Traits\WithUser;
use Illuminate\Support\Str;

class LoginTest extends TestCase
{
    use WithUser, ResponseStructure, RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupUser();
    }

    public function test_user_can_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => UserFactory::DEFAULT_PLAIN_PASSWORD,
            'token_name' => $this->faker->word(),
            'expired_at' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token' => [...$this->tokenStructure]])
            ->assertJson([
                'token' => [
                    'status' => 'Active',
                    'abilities' => ['*']
                ]
            ]);
    }

    public function test_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'wrong_password',
            'token_name' => $this->faker->word(),
            'expired_at' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ]);

        $response->assertUnprocessable()
            ->assertJsonStructure($this->httpApiExceptionStructure)
            ->assertJson(['detail' => 'These credentials do not match our records.']);
    }

    public function test_user_not_exists()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'notfound@example.com',
            'password' => 'password',
            'token_name' => $this->faker->word(),
            'expired_at' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ]);

        $response->assertUnprocessable()
            ->assertJsonStructure($this->httpApiExceptionStructure)
            ->assertJson(['detail' => 'These credentials do not match our records.']);
    }

    public function test_too_many_login_request()
    {
        /** @var RateLimiter $rateLimiter */
        $rateLimiter = $this->app->make(RateLimiter::class);
        $throttleKey = Str::lower("{$this->user->email}|") . request()->ip();

        collect(range(1, LoginRequest::MAX_ATTEMPT))->each(function () use ($rateLimiter, $throttleKey) {
            $this->app->call([$rateLimiter, 'hit'], ['key' => $throttleKey]);
        });

        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => UserFactory::DEFAULT_PLAIN_PASSWORD,
            'token_name' => $this->faker->word(),
            'expired_at' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ]);

        $response->assertStatus(429)
            ->assertJsonStructure($this->httpApiExceptionStructure);
    }
}

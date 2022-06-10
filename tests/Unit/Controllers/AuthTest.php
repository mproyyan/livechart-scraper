<?php

namespace Tests\Unit\Controllers;

use App\Contracts\UserInterface;
use App\Http\Requests\RegisterUserRequest;
use Mockery\MockInterface;
use Tests\TestCase;
use App\Http\Controllers\Api\AuthController;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Mockery;

class AuthTest extends TestCase
{
    use WithFaker;

    public function test_registration_method()
    {
        $this->mock(UserInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')->once()->with([])->andReturn(new User());
        });

        $this->mock(RegisterUserRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('validated')->once()->withNoArgs()->andReturn([]);
        });

        /** @var AuthController $auth */
        $auth = $this->app->make(AuthController::class);
        $user = $this->app->call([$auth, 'register']);

        $this->assertInstanceOf(JsonResponse::class, $user);
    }

    public function test_login_method()
    {
        $expiredAt = Carbon::createFromInterface($this->faker->dateTimeBetween('today', '+1 month'));

        /** @var PersonalAccessToken $tokenMock */
        $tokenMock = Mockery::mock(PersonalAccessToken::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAttribute', 'offsetExists');
        });

        $userMock = Mockery::mock(UserInterface::class, function (MockInterface $mock) use ($tokenMock, $expiredAt) {
            $mock->shouldReceive('createExpirableToken')
                ->once()
                ->with('main', $expiredAt)
                ->andReturn(new NewAccessToken($tokenMock, $this->faker->md5));
        });

        $this->mock(LoginRequest::class, function (MockInterface $mock) use ($expiredAt, $userMock) {
            $mock->shouldReceive('authenticateOrFail')->once()->withNoArgs();
            $mock->shouldReceive('user')->once()->withNoArgs()->andReturn($userMock);
            $mock->shouldReceive('input')->once()->with('token_name')->andReturn('main');
            $mock->shouldReceive('date')->once()->withSomeOfArgs('expired_at')->andReturn($expiredAt);
        });

        /** @var AuthController $auth */
        $auth = $this->app->make(AuthController::class);
        $token = $this->app->call([$auth, 'login']);

        $this->assertInstanceOf(JsonResponse::class, $token);
    }
}

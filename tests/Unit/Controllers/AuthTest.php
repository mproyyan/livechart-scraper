<?php

namespace Tests\Unit\Controllers;

use App\Contracts\UserInterface;
use App\Http\Requests\RegisterUserRequest;
use Mockery\MockInterface;
use Tests\TestCase;
use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthTest extends TestCase
{
    public function test_registration()
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
}

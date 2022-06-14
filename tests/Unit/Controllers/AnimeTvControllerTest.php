<?php

namespace Tests\Unit\Controllers;

use App\Contracts\AnimeTvInterface;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Tests\TestCase;
use App\Http\Controllers\Api\AnimeTvController;
use Illuminate\Http\JsonResponse;

class AnimeTvControllerTest extends TestCase
{
    public function test_call_anime_tv_controller_invokable_method_correctly()
    {
        $this->mock(Request::class, function (MockInterface $mock) {
            $mock->shouldReceive('query')->withSomeOfArgs('page')->andReturn(2);
            $mock->shouldReceive('query')->withSomeOfArgs('sortby')->andReturn('popularity');
            $mock->shouldReceive('query')->withSomeOfArgs('titles')->andReturn('romaji');
        });

        $this->mock(AnimeTvInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('all')
                ->once()
                ->andReturn(['animes' => [], 'pagination' => []]);
        });

        /** @var AnimeTvController $animeTvController */
        $animeTvController = $this->app->make(AnimeTvController::class);
        $data = $this->app->call($animeTvController);

        $this->assertInstanceOf(JsonResponse::class, $data);
    }
}

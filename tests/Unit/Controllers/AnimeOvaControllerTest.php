<?php

namespace Tests\Unit\Controllers;

use App\Contracts\AnimeOvaInterface;
use Tests\TestCase;
use Mockery\MockInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AnimeOvaController;
use Illuminate\Http\JsonResponse;

class AnimeOvaControllerTest extends TestCase
{
    public function test_call_anime_ova_controller_invokable_method_correctly()
    {
        $this->mock(Request::class, function (MockInterface $mock) {
            $mock->shouldReceive('query')->withSomeOfArgs('page')->andReturn(2);
            $mock->shouldReceive('query')->withSomeOfArgs('sortby')->andReturn('popularity');
            $mock->shouldReceive('query')->withSomeOfArgs('titles')->andReturn('romaji');
        });

        $this->mock(AnimeOvaInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('all')
                ->once()
                ->andReturn(['animes' => [], 'pagination' => []]);
        });

        /** @var AnimeOvaController $animeOvaController */
        $animeOvaController = $this->app->make(AnimeOvaController::class);
        $data = $this->app->call($animeOvaController);

        $this->assertInstanceOf(JsonResponse::class, $data);
    }
}

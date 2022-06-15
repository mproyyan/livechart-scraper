<?php

namespace Tests\Unit\Controllers;

use App\Contracts\AnimeMovieInterface;
use App\Http\Controllers\Api\AnimeMovieController;
use Tests\TestCase;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Illuminate\Http\JsonResponse;

class AnimeMovieControllerTest extends TestCase
{
    public function test_call_anime_movies_controller_invokable_method_correctly()
    {
        $this->mock(Request::class, function (MockInterface $mock) {
            $mock->shouldReceive('query')->withSomeOfArgs('page')->andReturn(2);
            $mock->shouldReceive('query')->withSomeOfArgs('sortby')->andReturn('popularity');
            $mock->shouldReceive('query')->withSomeOfArgs('titles')->andReturn('romaji');
        });

        $this->mock(AnimeMovieInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('all')
                ->once()
                ->andReturn(['animes' => [], 'pagination' => []]);
        });

        /** @var AnimeMovieController $animeMovieController */
        $animeMovieController = $this->app->make(AnimeMovieController::class);
        $data = $this->app->call($animeMovieController);

        $this->assertInstanceOf(JsonResponse::class, $data);
    }
}

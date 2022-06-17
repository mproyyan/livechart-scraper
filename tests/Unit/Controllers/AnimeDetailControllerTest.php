<?php

namespace Tests\Unit\Controllers;

use App\Contracts\AnimeDetailInterface;
use App\Models\AnimeDetail;
use Mockery\MockInterface;
use Tests\TestCase;
use App\Http\Controllers\Api\AnimeDetailController;
use Illuminate\Http\JsonResponse;

class AnimeDetailControllerTest extends TestCase
{
    public function test_call_anime_detail_controller_invokable_method_correctly()
    {
        /** @var AnimeDetailController $animeDetailController */
        $animeDetailController = $this->app->make(AnimeDetailController::class);
        $anime = $this->app->call($animeDetailController);

        $this->assertInstanceOf(JsonResponse::class, $anime);
    }
}

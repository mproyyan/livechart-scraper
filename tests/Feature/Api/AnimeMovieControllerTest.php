<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ResponseStructure;
use Tests\Traits\WithUser;

class AnimeMovieControllerTest extends TestCase
{
    use RefreshDatabase, WithUser, ResponseStructure;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupUser();
    }

    public function test_get_all_animes_movie_by_season_and_year()
    {
        $response = $this->withHeader('Authorization', $this->bearerToken)
            ->getJson('/api/movies');

        $response->assertOk()
            ->assertJsonStructure([
                'animes' => [$this->animeStructure],
                'pagination' => $this->paginationStructure
            ]);
    }

    public function test_unauthorized_user_cannot_get_all_animes_movie()
    {
        $response = $this->getJson('/api/tv');

        $response->assertUnauthorized()
            ->assertJsonStructure($this->httpApiExceptionStructure);
    }
}

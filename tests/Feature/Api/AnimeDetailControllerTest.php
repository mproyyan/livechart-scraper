<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ResponseStructure;
use Tests\Traits\WithUser;

class AnimeDetailControllerTest extends TestCase
{
    use RefreshDatabase, WithUser, ResponseStructure;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupUser();
    }

    public function test_get_anime_by_id()
    {
        $response = $this->withHeader('Authorization', $this->bearerToken)
            ->getJson('/api/anime/321');

        $response->assertOk()
            ->assertJsonStructure([
                'anime' => $this->animeStructure
            ]);
    }

    public function test_unauthorized_user_cannot_get_anime_by_id()
    {
        $response = $this->getJson('/api/anime/321');

        $response->assertUnauthorized()
            ->assertJsonStructure($this->httpApiExceptionStructure);
    }
}

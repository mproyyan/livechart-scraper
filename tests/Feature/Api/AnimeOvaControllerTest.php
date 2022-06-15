<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ResponseStructure;
use Tests\Traits\WithUser;

class AnimeOvaControllerTest extends TestCase
{
    use WithUser, ResponseStructure, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupUser();
    }

    public function test_get_all_animes_ova_by_season_and_year()
    {
        $response = $this->withHeader('Authorization', $this->bearerToken)
            ->getJson('/api/ovas');

        $response->assertOk()
            ->assertJsonStructure([
                'animes' => [$this->animeStructure],
                'pagination' => $this->paginationStructure
            ]);
    }

    public function test_unauthorized_user_cannot_get_all_animes_ova()
    {
        $response = $this->getJson('/api/ovas');

        $response->assertUnauthorized()
            ->assertJsonStructure($this->httpApiExceptionStructure);
    }
}

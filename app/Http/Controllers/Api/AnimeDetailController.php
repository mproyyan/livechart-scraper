<?php

namespace App\Http\Controllers\Api;

use App\Contracts\AnimeDetailInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnimeResource;
use App\Models\AnimeDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/** @property AnimeDetail $anime */
class AnimeDetailController extends Controller
{
    // public function __construct(
    //     private AnimeDetailInterface $anime
    // ) {
    // }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(AnimeDetailInterface $anime)
    {
        return (new AnimeResource($anime))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}

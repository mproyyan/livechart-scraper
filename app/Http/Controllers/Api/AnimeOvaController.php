<?php

namespace App\Http\Controllers\Api;

use App\Contracts\AnimeOvaInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnimeOva;
use App\Enums\SeasonEnum;
use App\Http\Resources\AnimeCollection;
use Illuminate\Http\Response;

/** @property AnimeOva $animeOva */
class AnimeOvaController extends Controller
{
    public function __construct(
        private AnimeOvaInterface $animeOva
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ?string $season = null, ?int $year = null)
    {
        $season = $season ?? SeasonEnum::getSeasonByMonth(now()->format('M'))->value;
        $year = $year ?? now()->format('Y');
        $sortBy = $request->query('sortby', 'popularity');
        $titles = $request->query('titles', 'romaji');
        $page = (int) $request->query('page', 1);

        $data = $this->animeOva->all($page, $season, $year, $sortBy, $titles);
        $animeCollection = (new AnimeCollection($data['animes']))
            ->additional([
                'pagination' => $data['pagination']
            ]);

        return $animeCollection
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}

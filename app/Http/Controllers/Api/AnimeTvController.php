<?php

namespace App\Http\Controllers\Api;

use App\Contracts\AnimeTvInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\SeasonEnum;
use App\Http\Resources\AnimeCollection;
use Illuminate\Http\Response;

/** @property \App\Models\AnimeTv $animeTv */
class AnimeTvController extends Controller
{
    public function __construct(
        private AnimeTvInterface $animeTv
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

        $data = $this->animeTv->all($page, $season, $year, $sortBy, $titles);
        $animeCollection = (new AnimeCollection($data['animes']))
            ->additional([
                'pagination' => $data['pagination']
            ]);

        return $animeCollection
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}

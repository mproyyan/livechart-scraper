<?php

namespace App\Http\Controllers\Api;

use App\Contracts\AnimeSearchInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnimeSearch;
use App\Http\Resources\AnimeCollection;
use Illuminate\Http\Response;

/** @property AnimeSearch $anime */
class AnimeSearchController extends Controller
{
    public function __construct(
        private AnimeSearchInterface $anime
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate(['query' => 'required']);

        $query = $request->query('query');
        $page = $request->query('page', 1);
        $titles = $request->query('titles', 'romaji');

        $data = $this->anime->all($query, $page, $titles);
        $animeCollection = (new AnimeCollection($data['animes']))
            ->additional([
                'pagination' => $data['pagination']
            ]);

        return $animeCollection
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}

<?php

namespace App\Models;

use App\Contracts\AnimeSearchInterface;
use Symfony\Component\DomCrawler\Crawler;
use App\Facades\Goutte;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Pagination\AnimePagination;

class AnimeSearch extends AnimeBaseModel implements AnimeSearchInterface
{
   public const BASE_URL = 'https://www.livechart.me/search';

   public const ANIME_PER_PAGE = 25;

   public function __construct(
      public ?int $id = null,
      public ?string $title = null,
      public ?string $image = null,
      public ?string $synopsis = null,
      public ?array $formatted_synopsis = null,
      public ?array $genres = null,
      public ?string $type = null,
      public ?string $source = null,
      public ?int $episodes = null,
      public ?array $duration = null,
      public ?array $aired = null,
      public ?string $season = null,
      public ?int $year = null,
      public ?array $studios = null
   ) {
   }

   public function all(string $query, int $page = 1, string $titles = 'romaji'): array
   {
      /** @var Crawler $crawler */
      $crawler = Goutte::request('GET', self::BASE_URL . '?' . Arr::query([
         'q' => $query,
         'page' => $page,
         'titles' => $titles,
      ]));

      $animes = [];
      $animeDetail = new AnimeDetail();

      $totalAnimes = (int) $this->countResultQuery($crawler->filter('div.options-bar-v2 > div.option-v2.text-secondary')->text(0));
      $animesPaginated = $crawler->filter('li.anime-item');
      $lastPage = ceil($totalAnimes / self::ANIME_PER_PAGE);

      $this->ensureAnimeFound($totalAnimes, $query);

      if ($page > $lastPage) {
         $page = $lastPage;
      }

      $ids = $this->getIds($animesPaginated);

      foreach ($ids as $id) {
         $anime = $animeDetail->find($id);
         $animes[] = new self(
            id: $anime->id,
            title: $anime->title,
            image: $anime->image,
            synopsis: $anime->synopsis,
            formatted_synopsis: $anime->formatted_synopsis,
            genres: $anime->genres,
            type: $anime->type,
            source: $anime->source,
            episodes: $anime->episodes,
            duration: $anime->duration,
            aired: $anime->aired,
            season: $anime->season,
            year: $anime->year,
            studios: $anime->studios,
         );
      }

      $pagination = (new AnimePagination($animesPaginated->count(), $totalAnimes, self::ANIME_PER_PAGE, $page))
         ->setPath("api/search")
         ->setQueryParams(['q' => $query, 'page' => $page, 'titles' => $titles])
         ->toArray();

      return [
         'animes' => $animes,
         'pagination' => $pagination
      ];
   }

   protected function getIds(Crawler $data)
   {
      return $data->each(function (Crawler $node) {
         return (int) $node->attr('data-anime-id');
      });
   }

   protected function ensureAnimeFound($count, $query)
   {
      if ($count < 1) {
         throw new NotFoundHttpException("Your search for [$query] did not return any results.");
      }
   }

   protected function countResultQuery(string $data)
   {
      return explode(' ', $data)[0];
   }
}

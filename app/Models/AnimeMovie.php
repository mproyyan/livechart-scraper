<?php

namespace App\Models;

use App\Contracts\AnimeMovieInterface;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;
use App\Enums\SeasonEnum;
use Illuminate\Support\Arr;
use App\Facades\Goutte;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Pagination\AnimePagination;

class AnimeMovie extends AnimeBaseModel implements AnimeMovieInterface
{
   public const BASE_URL = 'https://www.livechart.me/';

   public const ANIME_PER_PAGE = 25;

   public function __construct(
      private ?int $id = null,
      private ?string $title = null,
      private ?string $image = null,
      private ?string $synopsis = null,
      private ?array $formatted_synopsis = null,
      private ?array $genres = null,
      private ?string $type = null,
      private ?string $source = null,
      private ?int $episodes = null,
      private ?array $duration = null,
      private ?array $aired = null,
      private ?string $season = null,
      private ?int $year = null,
      private ?array $studios = null
   ) {
   }

   public function all(int $page = 1, ?string $season = null, ?int $year = null, string $sortBy = 'popularity', string $titles = 'romaji'): array
   {
      $season = $season ? Str::lower($season) : Str::lower(SeasonEnum::getSeasonByMonth(now()->format('M'))->value);
      $year = $year ?? now()->format('Y');

      $url = self::BASE_URL . Str::lower($season) . '-'  . $year . '/movies';

      /** @var Crawler $crawler */
      $crawler = Goutte::request('GET', $url . '?' . Arr::query([
         'sortby' => $sortBy,
         'titles' => $titles
      ]));

      $totalAnimes = $crawler->filter('article.anime')->count();
      $animesPaginated = $crawler->filter('article.anime')
         ->reduce(function (Crawler $node, $i) use ($page) {
            $index = $i + 1;
            $offset = 0 + (self::ANIME_PER_PAGE * ($page - 1));

            return $index > $offset && $index <= (self::ANIME_PER_PAGE * $page);
         });

      if ($animesPaginated->count() < 1) {
         throw new NotFoundHttpException("Data not found in current page [Page $page].");
      }

      $animes = $animesPaginated->each(function (Crawler $node) {
         return new self(
            id: $node->attr('data-anime-id'),
            title: $node->filter('.main-title a')->text(),
            image: $node->filter('.poster-container img')->attr('src'),
            synopsis: $this->getSynopsis($node->filter('.anime-info .anime-synopsis p')),
            formatted_synopsis: $this->getFormattedSynopsis($node->filter('.anime-info .anime-synopsis p')),
            genres: $this->getGenres($node->filter('.anime-card .anime-tags li a')),
            type: 'Movie',
            source: $node->filter('.anime-card .anime-info .anime-metadata .anime-source')->text(),
            episodes: null,
            duration: $this->getDuration($node->filter('.anime-card .anime-info .anime-metadata .anime-episodes')),
            aired: $this->getAiringProperties($node->filter('.anime-card .anime-info .anime-date')),
            season: $this->getSeason($node->filter('.anime-card .anime-info .anime-date')),
            year: $this->getYear($node->filter('.anime-card .anime-info .anime-date')),
            studios: $this->getStudios($node->filter('.anime-card .anime-info .anime-studios')->children())
         );
      });

      $pagination = (new AnimePagination($animesPaginated->count(), $totalAnimes, self::ANIME_PER_PAGE, $page))
         ->setPath("api/movies/$season/$year")
         ->setQueryParams(['sortby' => $sortBy, 'titles' => $titles])
         ->toArray();

      return [
         'animes' => $animes,
         'pagination' => $pagination
      ];
   }

   protected function getDuration(Crawler $node)
   {
      if (str_contains($node->text(), '?')) {
         return [
            'hours' => null,
            'minutes' => null,
            'seconds' => null,
            'total' => null,
         ];
      }

      return $this->formatDuration($node->text());
   }
}
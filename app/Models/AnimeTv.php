<?php

namespace App\Models;

use App\Contracts\AnimeTvInterface;
use App\Enums\SeasonEnum;
use App\Facades\Goutte;
use Illuminate\Contracts\Pagination\Paginator as PaginatorInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class AnimeTv extends BaseModel implements AnimeTvInterface
{
   public const BASE_URL = 'https://www.livechart.me/';

   public const ANIME_PER_PAGE = 20;

   protected string $primaryKey = 'id';

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

   public function all(?string $season, ?int $year, string $sortBy = 'popularity', string $titles = 'romaji'): PaginatorInterface
   {
      $season = $season ?? SeasonEnum::getSeasonByMonth(now()->format('M'))->value;
      $year = $year ?? now()->format('Y');

      $url = self::BASE_URL . Str::lower($season) . '-'  . $year . '/tv';

      /** @var Crawler $crawler */
      $crawler = Goutte::request('GET', $url . '?' . Arr::query([
         'sortby' => $sortBy,
         'titles' => $titles
      ]));

      $animes = $crawler->filter('article.anime')->each(function (Crawler $node) {
         return new self(
            id: $node->attr('data-anime-id'),
            title: $node->filter('.main-title a')->text(),
            image: $node->filter('.poster-container img')->attr('src'),
            synopsis: $this->getSynopsis($node->filter('.anime-info .anime-synopsis p')),
            formatted_synopsis: $this->getFormattedSynopsis($node->filter('.anime-info .anime-synopsis p')),
            genres: $this->getGenres($node->filter('.anime-card .anime-tags li a')),
            type: 'TV',
            source: $node->filter('.anime-card .anime-info .anime-metadata .anime-source')->text(),
            episodes: $this->getEpisodes($node->filter('.anime-card .anime-info .anime-metadata .anime-episodes')),
            duration: $this->getDuration($node->filter('.anime-card .anime-info .anime-metadata .anime-episodes')),
            aired: $this->getAiringProperties($node->filter('.anime-card .anime-info .anime-date')),
            season: $this->getSeason($node->filter('.anime-card .anime-info .anime-date')),
            year: $this->getYear($node->filter('.anime-card .anime-info .anime-date')),
            studios: $this->getStudios($node->filter('.anime-card .anime-info .anime-studios')->children())
         );
      });

      $paginator = new Paginator($animes, self::ANIME_PER_PAGE);

      return $paginator;
   }

   public function find(string $value): self
   {
      return $this;
   }

   protected function getEpisodes(Crawler $node)
   {
      $rawText = explode('×', $node->text());

      if (count($rawText) <= 1) {
         return null;
      }

      $episode = explode(' ', $rawText[0])[0];

      if ($episode === '?') {
         return null;
      }

      return (int) $episode;
   }

   protected function getDuration(Crawler $node)
   {
      $rawText = explode('×', $node->text());

      if (count($rawText) <= 1) {
         return null;
      }

      $duration = trim($rawText[1]);

      if (explode(' ', $duration)[0] === '?') {
         return null;
      }

      return $this->formatDuration($duration);
   }
}

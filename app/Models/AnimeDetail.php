<?php

namespace App\Models;

use App\Contracts\AnimeDetailInterface;
use App\Facades\Goutte;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AnimeDetail extends AnimeBaseModel implements AnimeDetailInterface
{
   public const BASE_URL = 'https://www.livechart.me/anime/';

   /**
    * The primary key for the model.
    *
    * @var string
    */
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

   public function find(int $id)
   {
      $anime = Cache::tags(['anime-detail'])->get("anime-detail-id-$id");

      if (!is_null($anime)) {
         return $anime;
      }

      /** @var Crawler $crawler */
      $crawler = Goutte::request('GET',  self::BASE_URL . $id);

      if ($crawler->filter('#content')->count() < 1) {
         throw new NotFoundHttpException("Cannot find anime with that id [$id]");
      }

      $this->id = $crawler->filter('#content > div.row')->attr('data-anime-details-id');
      $this->title = $crawler->filter('div.column > h4')->innerText();
      $this->image = $crawler->filter('div.anime-poster img')->attr('src');
      $this->synopsis = $this->getSynopsis($crawler->filter('.expandable-text-body p'));
      $this->formatted_synopsis = $this->getFormattedSynopsis($crawler->filter('.expandable-text-body p'));
      $this->genres = $this->getGenres($crawler->filter('div.row div.column.medium-6')->eq(0)->filter('li a'));
      $this->type = $this->getAnimeMetaData($crawler->filter('div.anime-meta-bar div.info-bar-cell'))['type'];
      $this->source = $this->getAnimeMetaData($crawler->filter('div.anime-meta-bar div.info-bar-cell'))['source'];
      $this->episodes = $this->getAnimeMetaData($crawler->filter('div.anime-meta-bar div.info-bar-cell'))['episodes'];
      $this->duration = $this->getAnimeMetaData($crawler->filter('div.anime-meta-bar div.info-bar-cell'))['duration'];
      $this->aired = $this->getAiringProperties($crawler->filter('.section-body small > a'));
      $this->season = $this->getSeason($crawler->filter('.section-body small > a'));
      $this->year = $this->getYear($crawler->filter('.section-body small > a'));
      $this->studios = $this->getStudios($crawler->filter('div.row div.column.medium-6')->eq(1)->filter('li a'));

      Cache::tags(['anime-detail'])->put("anime-detail-id-$id", $this, now()->addHours(12));
      return $this;
   }

   /**
    * Get the value of the model's route key.
    *
    * @return int|string
    */
   public function getRouteKey(): int|string
   {
      return $this->{$this->primaryKey};
   }

   /**
    * Get the route key for the model.
    *
    * @return string
    */
   public function getRouteKeyName(): string
   {
      return $this->getKeyName();
   }

   /**
    * Get the primary key for the model.
    *
    * @return string
    */
   public function getKeyName()
   {
      return $this->primaryKey;
   }

   /**
    * Retrieve the model for a bound value.
    *
    * @param  string  $value
    * @param  string|null  $field
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   public function resolveRouteBinding($value, $field = null)
   {
      /** @phpstan-ignore-next-line */
      return $this->find($value);
   }

   /**
    * Retrieve the child model for a bound value.
    *
    * @param  string  $childType
    * @param  mixed  $value
    * @param  string|null  $field
    *
    * @throws \Exception
    */
   public function resolveChildRouteBinding($childType, $value, $field)
   {
      throw new \Exception(self::class . ' does not support child bindings.');
   }
}

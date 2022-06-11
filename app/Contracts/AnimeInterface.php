<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\Paginator;

interface AnimeInterface extends BaseModelInterface
{
   /**
    * Create new anime instance
    */
   public function __construct(
      ?int $id = null,
      ?string $title = null,
      ?string $image = null,
      ?string $synopsis = null,
      ?array $formatted_synopsis = null,
      ?array $genres = null,
      ?string $type = null,
      ?string $source = null,
      ?int $episodes = null,
      ?string $status = null,
      ?array $duration = null,
      ?array $aired = null,
      ?string $season = null,
      ?int $year = null,
      ?array $studios = null
   );

   /**
    * Get all animes in the current page.
    *
    * @return Paginator
    */
   public function all(string $season, int $year, string $sortBy = 'popularity', string $titles = 'romaji'): Paginator;

   /**
    * Get a anime by id.
    *
    * @param string $slug
    *
    * @return self
    */
   public function find(string $slug): self;
}

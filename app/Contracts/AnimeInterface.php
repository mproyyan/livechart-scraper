<?php

namespace App\Contracts;

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
      ?array $duration = null,
      ?array $aired = null,
      ?string $season = null,
      ?int $year = null,
      ?array $studios = null
   );

   /**
    * Get all animes in the current page.
    *
    * @return Array
    */
   public function all(int $page = 1, ?string $season = null, ?int $year = null, string $sortBy = 'popularity', string $titles = 'romaji'): array;

   /**
    * Get a anime by id.
    *
    * @param string $slug
    *
    * @return self
    */
   public function find(string $slug): self;
}

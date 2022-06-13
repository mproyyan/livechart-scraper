<?php

namespace App\Contracts;

interface AnimeInterface
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
}

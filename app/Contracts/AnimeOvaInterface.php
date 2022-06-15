<?php

namespace App\Contracts;

interface AnimeOvaInterface extends AnimeInterface
{
   /**
    * Get all animes in the current page.
    *
    * @return Array
    */
   public function all(int $page = 1, ?string $season = null, ?int $year = null, string $sortBy = 'popularity', string $titles = 'romaji'): array;
}

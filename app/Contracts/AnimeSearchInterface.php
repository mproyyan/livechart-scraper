<?php

namespace App\Contracts;

interface AnimeSearchInterface extends AnimeInterface
{
   /**
    * Get all animes in the current page.
    *
    * @return Array
    */
   public function all(int $page = 1, string $titles = 'romaji'): array;
}

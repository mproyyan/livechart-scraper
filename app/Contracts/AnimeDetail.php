<?php

namespace App\Contracts;

use Illuminate\Contracts\Routing\UrlRoutable;

interface AnimeDetailInterface extends AnimeInterface, UrlRoutable
{
   public function find(int $id);
}

<?php

namespace App\Contracts;

use Laravel\Sanctum\Contracts\HasApiTokens;
use Illuminate\Support\Carbon;

interface UserInterface extends HasApiTokens
{
   /**
    * Create a expirable new personal access token for the user.
    *
    * @param string $name
    * @param Carbon|null $expiredAt
    * @param array  $abilities
    *
    * @return \Laravel\Sanctum\NewAccessToken
    */
   public function createExpirableToken(string $name = 'main', ?Carbon $expiredAt, array $abilities = ['*']);
}

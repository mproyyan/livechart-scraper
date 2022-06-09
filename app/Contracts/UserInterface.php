<?php

namespace App\Contracts;

use Laravel\Sanctum\Contracts\HasApiTokens;

interface UserInterface extends HasApiTokens
{
   /**
    * Create a expirable new personal access token for the user.
    *
    * @param string $name
    * @param string|null $expiredAt
    * @param array  $abilities
    *
    * @return \Laravel\Sanctum\NewAccessToken
    */
   public function createExpirableToken(string $name = 'main', ?string $expiredAt, array $abilities = ['*']);
}

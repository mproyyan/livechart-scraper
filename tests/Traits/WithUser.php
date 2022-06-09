<?php

namespace Tests\Traits;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Database\Factories\PersonalAccessTokenFactory;

trait WithUser
{
   /** @var User $user */
   protected User $user;

   protected string $token;

   protected function setupUser()
   {
      $this->user = User::factory()
         ->has(PersonalAccessToken::factory(), 'tokens')
         ->create();

      $tokenId = $this->user->tokens->first()->id;
      $plainTextToken = PersonalAccessTokenFactory::DEFAULT_PLAIN_TEXT_TOKEN;
      $this->token = $tokenId . '|' . $plainTextToken;
   }
}

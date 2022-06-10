<?php

namespace Tests\Traits;

trait ResponseStructure
{
   protected $httpApiExceptionStructure = [
      'status',
      'type',
      'title',
      'detail'
   ];

   protected $tokenStructure = [
      'token_name',
      'abilities',
      'expired_at',
      'token',
      'status'
   ];
}

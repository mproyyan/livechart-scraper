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

   protected $animeStructure = [
      'id',
      'title',
      'image',
      'synopsis',
      'formatted_synopsis',
      'genres',
      'type',
      'source',
      'episodes',
      'duration',
      'aired',
      'season',
      'year',
      'studios'
   ];

   protected $paginationStructure = [
      'current_page',
      'last_page',
      'from',
      'to',
      'items',
      'links'
   ];
}

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
}

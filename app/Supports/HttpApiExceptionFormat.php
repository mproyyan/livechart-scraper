<?php

namespace App\Supports;

use Phpro\ApiProblem\Http\HttpApiProblem;

class HttpApiExceptionFormat extends HttpApiProblem
{
   public function __construct(int $statusCode, array $data)
   {
      parent::__construct($statusCode, $data);
   }
}

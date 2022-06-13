<?php

namespace App\Pagination;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class AnimePagination implements Arrayable
{
   /** @var array $data */
   private $data;

   /** @var string $path */
   private $path = '';

   /** @var array $queryParams */
   private $queryParams = [];

   public function __construct(int $itemsCount, int $total, int $perPage, int $currentPage)
   {
      $this->data = [
         'current_page' => $currentPage,
         'last_page' => (int) ceil($total / $perPage),
         'from' => 1 + ($perPage * ($currentPage - 1)),
         'to' => ((1 + ($perPage * ($currentPage - 1))) + $itemsCount) - 1,
         'items' => [
            'count' => $itemsCount,
            'per_page' => $perPage,
            'total' => $total
         ],
         'links' => [
            'first' => url($this->path . '?' . Arr::query([
               'page' => 1,
               ...$this->queryParams
            ])),
            'last' => url($this->path . '?' . Arr::query([
               'page' => (int) ceil($total / $perPage),
               ...$this->queryParams
            ])),
            'prev' => $currentPage > 1 ? url($this->path . '?' . Arr::query([
               'page' => $currentPage - 1,
               ...$this->queryParams
            ])) : null,
            'next' => $currentPage < (int) ceil($total / $perPage) ? url($this->path . '?' . Arr::query([
               'page' => $currentPage + 1,
               ...$this->queryParams
            ])) : null,
         ]
      ];
   }

   public function toArray()
   {
      return $this->data;
   }

   public function setPath(string $path)
   {
      $this->path = $path;

      return $this;
   }

   public function setQueryParams(array $parameters = [])
   {
      $this->queryParams = $parameters;

      return $this;
   }
}

<?php

namespace App\Pagination;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class AnimePagination implements Arrayable
{
   /** @var string $path */
   private $path = '';

   /** @var array $queryParams */
   private $queryParams = [];

   /** @var int $currentPage */
   private int $currentPage;

   /** @var int $perPage */
   private int $perPage;

   /** @var int $itemsCount */
   private int $itemsCount;

   /** @var int $total */
   private int $total;

   public function __construct(int $itemsCount, int $total, int $perPage, int $currentPage)
   {
      $this->itemsCount = $itemsCount;
      $this->total = $total;
      $this->perPage = $perPage;
      $this->currentPage = $currentPage;
   }

   public function toArray()
   {
      return $this->data = [
         'current_page' => $this->currentPage,
         'last_page' => (int) ceil($this->total / $this->perPage),
         'from' => 1 + ($this->perPage * ($this->currentPage - 1)),
         'to' => ((1 + ($this->perPage * ($this->currentPage - 1))) + $this->itemsCount) - 1,
         'items' => [
            'count' => $this->itemsCount,
            'per_page' => $this->perPage,
            'total' => $this->total
         ],
         'links' => [
            'first' => url($this->path . '?' . Arr::query([
               'page' => 1,
               ...$this->queryParams
            ])),
            'last' => url($this->path . '?' . Arr::query([
               'page' => (int) ceil($this->total / $this->perPage),
               ...$this->queryParams
            ])),
            'prev' => $this->currentPage > 1 ? url($this->path . '?' . Arr::query([
               'page' => $this->currentPage - 1,
               ...$this->queryParams
            ])) : null,
            'next' => $this->currentPage < (int) ceil($this->total / $this->perPage) ? url($this->path . '?' . Arr::query([
               'page' => $this->currentPage + 1,
               ...$this->queryParams
            ])) : null,
         ]
      ];
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

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimeResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'anime';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'synopsis' => $this->synopsis,
            'formatted_synopsis' => $this->formatted_synopsis,
            'genres' => $this->genres,
            'type' => $this->type,
            'source' => $this->source,
            'episodes' => $this->episodes,
            'duration' => $this->duration,
            'aired' => $this->aired,
            'season' => $this->season,
            'year' => $this->year,
            'studios' => $this->studios,
        ];
    }
}

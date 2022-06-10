<?php

namespace App\Http\Resources;

use App\Enums\TokenStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalAccessTokenResource extends JsonResource
{
    public static $wrap = 'token';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'token_name' => $this->name,
            'abilities' => $this->abilities,
            'expired_at' => $this->expired_at ?? null
        ];
    }
}

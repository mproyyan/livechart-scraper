<?php

namespace App\Http\Resources;

use App\Enums\TokenStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalAccessTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'token_name' => $this->accessToken->name,
            'abilities' => $this->accessToken->abilities,
            'expired_at' => $this->accessToken->expired_at ?? null,
            'token' => $this->plainTextToken,
            'status' => TokenStatusEnum::Active
        ];
    }
}

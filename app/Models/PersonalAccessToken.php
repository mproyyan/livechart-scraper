<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
   /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
   protected $casts = [
      'abilities' => 'json',
      'last_used_at' => 'datetime',
      'expired_at' => 'datetime',
   ];

   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
      'name',
      'token',
      'abilities',
      'expired_at',
   ];

   /**
    * Set expiry date format.
    *
    * @return \Illuminate\Database\Eloquent\Casts\Attribute<mixed, mixed>
    */
   public function expiredAt(): Attribute
   {
      return new Attribute(
         set: fn (Carbon $value): Carbon => $value->setHour(23)->setMinute(59)->setSecond(59),
      );
   }
}

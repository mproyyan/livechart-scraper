<?php

namespace App\Enums;

enum TokenStatusEnum: String
{
   case Active = 'Active';
   case Expired = 'Expired';
   case Revoked = 'Revoked';
}

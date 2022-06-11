<?php

namespace App\Enums;

enum SeasonEnum: string
{
   case Winter = 'Winter';
   case Spring = 'Spring';
   case Summer = 'Summer';
   case Fall = 'Fall';

   public function season($month)
   {
      return match ($month) {
         'Jan', 'January', 'Feb', 'February', 'Mar', 'March' => self::Winter,
         'Apr', 'April', 'May', 'Jun', 'June' => self::Spring,
         'Jul', 'July', 'Aug', 'August', 'Sep', 'September' => self::Summer,
         'Oct', 'October', 'Nov', 'November', 'Dec', 'December' => self::Fall
      };
   }
}

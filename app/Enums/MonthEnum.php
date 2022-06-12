<?php

namespace App\Enums;

enum MonthEnum: int
{
   case January = 1;
   case February = 2;
   case March = 3;
   case April = 4;
   case May = 5;
   case June = 6;
   case July = 7;
   case August = 8;
   case September = 9;
   case October = 10;
   case November = 11;
   case December = 12;

   public static function convertToNumber($month)
   {
      return match ($month) {
         'January', 'Jan', 'january', 'jan' => 1,
         'February', 'Feb', 'february', 'feb' => 2,
         'March', 'Mar', 'march', 'mar' => 3,
         'April', 'Apr', 'april', 'apr' => 4,
         'May', 'may' => 5,
         'June', 'Jun', 'june', 'jun' => 6,
         'July', 'Jul', 'july', 'jul' => 7,
         'August', 'Aug', 'august', 'aug' => 8,
         'September', 'Sep', 'september', 'sep' => 9,
         'October', 'Oct', 'october', 'oct' => 10,
         'November', 'Nov', 'november', 'nov' => 11,
         'December', 'Dec', 'december', 'dec' => 12,
      };
   }
}

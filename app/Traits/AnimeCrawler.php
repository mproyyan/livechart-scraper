<?php

namespace App\Traits;

use App\Enums\MonthEnum;
use App\Enums\SeasonEnum;
use Symfony\Component\DomCrawler\Crawler;

trait AnimeCrawler
{
   protected function getSynopsis(Crawler $node)
   {
      $arrayOfSynopsis = $node->reduce(function (Crawler $node) {
         return $node->matches('.editor-note') ? false : true;
      })->each(function (Crawler $node) {
         return $node->text();
      });

      return join(' ', $arrayOfSynopsis);
   }

   protected function getFormattedSynopsis(Crawler $node)
   {
      return $node->reduce(function (Crawler $node) {
         return $node->matches('.editor-note') ? false : true;
      })->each(function (Crawler $node) {
         return $node->text();
      });
   }

   protected function getGenres(Crawler $node)
   {
      return $node->each(function (Crawler $node) {
         $id = explode('/', $node->attr('href'));
         $genre = $node->text();

         return [
            'genre_id' => (int) end($id),
            'genre' => $genre
         ];
      });
   }

   protected function getAiringProperties(Crawler $node)
   {
      return [
         'premiere' => $this->formatPremiere($node->text()),
         'props' => $this->formatAiringProps($node->text()),
      ];
   }

   protected function getSeason(Crawler $node)
   {
      return $this->formatSeasonAndYear($node->text())['season'];
   }

   protected function getYear(Crawler $node)
   {
      return $this->formatSeasonAndYear($node->text())['year'];
   }

   protected function getStudios(Crawler $node)
   {
      return $node->each(function (Crawler $node) {
         return $node->text();
      });
   }

   protected function getEpisodes(Crawler $node)
   {
      if ($episodes = $this->hasEpisode($node->text())) {
         return $episodes;
      }

      return null;
   }

   protected function getDuration(Crawler $node)
   {
      if ($duration = $this->hasDuration($node->text())) {
         return $this->formatDuration($duration);
      }

      return [
         'hours' => null,
         'minutes' => null,
         'seconds' => null,
      ];
   }

   protected function getAnimeMetaData(Crawler $node)
   {
      $type = null;
      $source = null;
      $episodes = 1;
      $duration = null;

      $node->each(function (Crawler $node) use (&$type, &$source, &$episodes, &$duration) {
         if (preg_match_all('/format/i', $node->filter('.info-bar-cell-label')->text(), $matches)) {
            $type = $node->filter('.info-bar-cell-value')->text();
         }

         if (preg_match_all('/source/i', $node->filter('.info-bar-cell-label')->text(), $matches)) {
            $source = $node->filter('.info-bar-cell-value')->text();
         }

         if (preg_match_all('/episodes?/i', $node->filter('.info-bar-cell-label')->text(), $matches)) {
            $episodes = $node->filter('.info-bar-cell-value')->text();

            if (str_contains('?', $episodes)) {
               $episodes = null;
            }
         }

         if (preg_match_all('/run time/i', $node->filter('.info-bar-cell-label')->text(), $matches)) {
            $duration = $node->filter('.info-bar-cell-value')->text();

            if ($data = $this->hasDuration($duration)) {
               $duration = $this->formatDuration($data);
            } else {
               $duration = [
                  'hours' => null,
                  'minutes' => null,
                  'seconds' => null,
               ];
            }
         }
      });

      return [
         'type' => $type,
         'source' => $source,
         'episodes' => $episodes,
         'duration' => $duration,
      ];
   }

   protected function formatPremiere($text)
   {
      $rawData = explode(',', $text);

      if (count($rawData) === 2) {
         $season = (SeasonEnum::getSeasonByMonth(explode(' ', $rawData[0])[0]))->value;
         $year = explode(' ', trim($rawData[1], ' '))[0];

         return "$season $year";
      }

      $rawData = explode(' ', $text);

      if (count($rawData) === 3) {
         $season = $rawData[1];
         $year = $rawData[2];

         return "$season $year";
      }

      if (count($rawData) === 2) {
         $season = SeasonEnum::tryFrom($rawData[0]) ? SeasonEnum::tryFrom($rawData[0])->value : (SeasonEnum::getSeasonByMonth($rawData[0]))->value;
         $year = $rawData[1];

         return "$season $year";
      }

      if (count($rawData) === 1) {
         return (string) $rawData[0];
      }

      return 'Unknown';
   }

   protected function formatAiringProps($text)
   {
      $rawData = explode(',', $text);

      if (count($rawData) === 2) {
         return [
            'day' => (int) explode(' ', $rawData[0])[1],
            'month' => (int) MonthEnum::convertToNumber(explode(' ', $rawData[0])[0]),
            'year' => (int) explode(' ', trim($rawData[1], ' '))[0]
         ];
      }

      $rawData = explode(' ', $text);

      if (count($rawData) === 3) {
         return [
            'day' => null,
            'month' => null,
            'year' => (int) $rawData[2]
         ];
      }

      if (count($rawData) === 2) {
         return [
            'day' => null,
            'month' => MonthEnum::convertToNumber($rawData[0]) ?? null,
            'year' => (int) $rawData[1]
         ];
      }

      if (count($rawData) === 1) {
         return [
            'day' => null,
            'month' => null,
            'year' => (int) $rawData[0]
         ];
      }

      return [
         'day' => null,
         'month' => null,
         'year' => null
      ];
   }

   protected function formatSeasonAndYear($text)
   {
      $rawData = explode(',', $text);

      if (count($rawData) === 2) {
         $season = (SeasonEnum::getSeasonByMonth(explode(' ', $rawData[0])[0]))->value;
         $year = explode(' ', trim($rawData[1], ' '))[0];

         return [
            'season' => $season,
            'year' => (int) $year
         ];
      }

      $rawData = explode(' ', $text);

      if (count($rawData) === 3) {
         $season = $rawData[1];
         $year = (int) $rawData[2];

         return [
            'season' => $season,
            'year' => (int) $year
         ];
      }

      if (count($rawData) === 2) {
         $season = (SeasonEnum::getSeasonByMonth($rawData[0]))->value;
         $year = $rawData[1];

         return [
            'season' => $season,
            'year' => (int) $year
         ];
      }

      if (count($rawData) === 1) {
         if (preg_match_all('/[0-9]+/', $rawData[0], $matches)) {
            return [
               'season' => null,
               'year' => (int) $rawData[0]
            ];
         }

         return [
            'season' => null,
            'year' => null,
         ];
      }

      return [
         'season' => null,
         'year' => null,
      ];
   }

   protected function formatDuration($time)
   {
      $pattern = '/([0-9]+[h|m|s]) ?([0-9]+[m|s])? ?([0-9]+s)?/';

      $hour = 0;
      $minute = 0;
      $second = 0;

      if (preg_match_all($pattern, $time, $matches)) {
         if (str_contains($matches[1][0], 'h')) {
            $hour = substr($matches[1][0], 0, strpos($matches[1][0], 'h'));
         }

         if (str_contains($matches[1][0], 'm')) {
            $minute = substr($matches[1][0], 0, strpos($matches[1][0], 'm'));
         }

         if (str_contains($matches[1][0], 's')) {
            $second = substr($matches[1][0], 0, strpos($matches[1][0], 's'));
         }

         if (!empty($matches[2][0])) {
            if (str_contains($matches[2][0], 'm')) {
               $minute = substr($matches[2][0], 0, strpos($matches[2][0], 'm'));
            }

            if (str_contains($matches[2][0], 's')) {
               $second = substr($matches[2][0], 0, strpos($matches[2][0], 's'));
            }
         }

         if (!empty($matches[3][0])) {
            $second = substr($matches[3][0], 0, strpos($matches[3][0], 's'));
         }
      }

      return [
         'hours' => (int) $hour,
         'minutes' => (int) $minute,
         'seconds' => (int) $second,
      ];
   }

   protected function hasEpisode(string $data)
   {
      $pattern = '/([0-9?]+ eps)/';

      if (preg_match_all($pattern, $data, $matches)) {
         $eps = $matches[0][0];

         if (str_contains('?', $eps)) {
            return false;
         }

         return (int) explode(' ', $eps)[0];
      }

      return (int) 1;
   }

   protected function hasDuration(string $data)
   {
      $pattern = '/([0-9]+[h|m|s]) ?([0-9]+[m|s])? ?([0-9]+s)?/';

      if (preg_match_all($pattern, $data, $matches)) {
         return $matches[0][0];
      }

      return false;
   }
}

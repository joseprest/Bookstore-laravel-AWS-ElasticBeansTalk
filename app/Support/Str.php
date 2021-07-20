<?php namespace Manivelle\Support;

use Illuminate\Support\Str as BaseStr;

use Carbon\Carbon;

class Str extends BaseStr
{
    
    public static function formatDaterange($start, $end)
    {
        $startDate = $start ? Carbon::parse($start):null;
        $endDate = $end ? Carbon::parse($end):null;
        
        $time = [];
        $startTime = $startDate && ($startDate->hour || $startDate->minute || $startDate->second)
            ? self::formatTime($startDate):null;
        $endTime = $endDate && ($endDate->hour || $endDate->minute || $endDate->second)
            ? self::formatTime($endDate):null;
        if ($startTime) {
            $time[] = $startTime;
        }
        if ($endTime && $endTime !== $startTime) {
            $time[] = $endTime;
        }
        
        $date = [];
        if ($startDate && $startDate->year !== -1) {
            $date[] = self::formatDate($startDate, '%e %B', false);
        }
        if ($endDate && $endDate->year !== -1 &&
            (!$startDate || $startDate->toDateString() !== $endDate->toDateString())
        ) {
            $date[] = self::formatDate($endDate, '%e %B', false);
        }
        
        if (!sizeof($date)) {
            return null;
        }
        
        $date = (sizeof($date) === 1 ? 'Le':'Du').' '.implode(' au ', $date);
        if (sizeof($time) === 1) {
            return $date.' à '.$time[0];
        } elseif (sizeof($time) === 2) {
            return $date.' de '.$time[0].' à '.$time[1];
        }
        
        return $date;
    }
    
    public static function formatDate($date, $format = '%e %B', $withTime = true)
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }
        
        if ($date->year <= 0) {
            return null;
        }
        
        if ($withTime && ($date->hour || $date->minute || $date->second)) {
            return trim($date->formatLocalized('%A %e %B').', '.self::formatTime($date));
        } else {
            return trim($date->formatLocalized($format));
        }
    }
    
    public static function formatTime($date)
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }
        
        if ($date->hour && !$date->minute) {
            return trim($date->formatLocalized('%kh'));
        } else {
            return trim($date->formatLocalized('%kh%M'));
        }
    }
}

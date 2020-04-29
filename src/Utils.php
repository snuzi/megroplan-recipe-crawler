<?php
namespace Megroplan\Crawler;

class Utils {

    static function ISO8601FormatToMinutes($time) {
        try {
            $interval = new \DateInterval($time);

            return ($interval->d * 24 * 60) + ($interval->h * 60) + $interval->i;
        } catch (\Exception $e) {
            return null;
        }
    }

    static function getFloatFromString($str)
    {
        preg_match_all('!\d+(?:\.\d+)?!', $str, $matches);
        $floats = array_map('floatval', $matches[0]);
        
        return $floats ? $floats[0] : null;
    }
}
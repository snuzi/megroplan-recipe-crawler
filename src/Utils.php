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
}
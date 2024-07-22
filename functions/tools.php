<?php

declare(strict_types=1);

/**
 * Load an object with data in json format
 *
 * @param mixed $object
 * @param string $json
 * 
 * @return mixed
 */
function load_object_json(mixed $object, string $json): mixed
{
    $dcod = json_decode($json);
    $prop = get_object_vars($dcod);

    foreach ($prop as $key => $lock) {
        if (property_exists($object,  $key)) {
            if (is_object($dcod->$key)) {
                load_object_json($object->$key, json_encode($dcod->$key));
            } else {
                $object->$key = $dcod->$key;
            }
        }
    }

    return $object;
}

/**
 * Return period in hours, minutes or seconds using microtime function
 *
 * @param float $endtime
 * @param float $starttime
 * 
 * @return mixed
 */
function microtime_period(float $endtime, float $starttime): mixed
{
    $duration = $endtime - $starttime;
    $hours = (int) ($duration / 60 / 60);
    $minutes = (int) ($duration / 60) - $hours * 60;
    $seconds = (int) $duration - $hours * 60 * 60 - $minutes * 60;
    return ($hours == 0 ? "00" : $hours) . ":" . ($minutes == 0 ? "00" : ($minutes < 10 ? "0" . $minutes : $minutes)) . ":" . ($seconds == 0 ? "00" : ($seconds < 10 ? "0" . $seconds : $seconds));
}

if (!function_exists('hex2rgb')) {
    /**
     * Takes HEX color code value and converts to a RGB value.
     *
     * @param string $color Color hex value, example: #000000, #000 or 000000, 000
     *
     * @return string color rbd value
     */
    function hex2rgb(string $color): string
    {
        $color = str_replace('#', '', $color);

        $hex = strlen($color) == 3
            ? [$color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]]
            : [$color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]];

        list($r, $g, $b) = $hex;

        return sprintf(
            'rgb(%s, %s, %s)',
            hexdec($r),
            hexdec($g),
            hexdec($b)
        );
    }
}

if (!function_exists('rgb2hex')) {
    /**
     * Takes RGB color value and converts to a HEX color code
     * Could be used as Recipe::rgb2hex("rgb(0,0,0)") or Recipe::rgb2hex(0,0,0).
     *
     * @param mixed $r Full rgb,rgba string or red color segment
     * @param mixed $g null or green color segment
     * @param mixed $b null or blue color segment
     *
     * @return string hex color value
     */
    function rgb2hex(mixed $r, mixed $g = null, mixed $b = null): string
    {
        if (strpos($r, 'rgb') !== false || strpos($r, 'rgba') !== false) {
            if (preg_match_all('/\(([^\)]*)\)/', $r, $matches) && isset($matches[1][0])) {
                list($r, $g, $b) = explode(',', $matches[1][0]);
            } else {
                return "";
            }
        }

        $result = '';
        foreach ([$r, $g, $b] as $c) {
            $hex = base_convert($c, 10, 16);
            $result .= ($c < 16) ? ('0' . $hex) : $hex;
        }

        return '#' . $result;
    }
}

if (!function_exists('number_to_word')) {
    /**
     * Convert number to word representation.
     *
     * @param int $number number to convert to word
     *
     * @return string converted string
     * @throws \Exception
     */
    function number_to_word(int $number): string
    {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $fraction = null;
        $dictionary = [
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion',
        ];

        if (!is_numeric($number)) throw new \Exception('NaN');

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            throw new \Exception('numberToWord only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX);
        }

        if ($number < 0) return $negative . number_to_word(abs($number));
        if (strpos((string)$number, '.') !== false) list($number, $fraction) = explode('.', (string)$number);

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;

            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];

                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }

                break;

            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];

                if ($remainder) {
                    $string .= $conjunction . number_to_word($remainder);
                }

                break;

            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = number_to_word($numBaseUnits) . ' ' . $dictionary[$baseUnit];

                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= number_to_word($remainder);
                }

                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = [];

            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }

            $string .= implode(' ', $words);
        }

        return $string;
    }
}

if (!function_exists('seconds_to_text')) {
    /**
     * Convert seconds to real time.
     *
     * @param int  $seconds       time in seconds
     * @param bool $returnAsWords return time in words (example one minute and 20 seconds) if value is True or (1 minute and 20 seconds) if value is false, default false
     *
     * @return string
     */
    function seconds_to_text(int $seconds, bool $returnAsWords = false): string
    {
        $periods = [
            'year'   => 3.156e+7,
            'month'  => 2.63e+6,
            'week'   => 604800,
            'day'    => 86400,
            'hour'   => 3600,
            'minute' => 60,
            'second' => 1,
        ];

        $parts = [];
        foreach ($periods as $name => $dur) {
            $div = floor($seconds / $dur);

            if ($div == 0) continue;

            if ($div == 1) {
                $parts[] = ($returnAsWords ? number_to_word((int)$div) : $div) . ' ' . $name;
            } else {
                $parts[] = ($returnAsWords ? number_to_word((int)$div) : $div) . ' ' . $name . 's';
            }

            $seconds %= $dur;
        }

        $last = array_pop($parts);
        if (empty($parts)) return $last;
        return implode(', ', $parts) . ' and ' . $last;
    }
}

if (!function_exists('minutes_to_text')) {
    /**
     * Convert minutes to real time.
     *
     * @param int  $minutes       time in minutes
     * @param bool $returnAsWords return time in words (example one hour and 20 minutes) if value is True or (1 hour and 20 minutes) if value is false, default false
     *
     * @return string
     */
    function minutes_to_text(int $minutes, bool $returnAsWords = false): string
    {
        return seconds_to_text($minutes * 60, $returnAsWords);
    }
}

if (!function_exists('hours_to_text')) {
    /**
     * Convert hours to real time.
     *
     * @param int  $hours         time in hours
     * @param bool $returnAsWords return time in words (example one hour) if value is True or (1 hour) if value is false, default false
     *
     * @return string
     */
    function hours_to_text(int $hours, bool $returnAsWords = false): string
    {
        return seconds_to_text($hours * 3600, $returnAsWords);
    }
}

if (!function_exists('number_days_in_month')) {
    /**
     * Returns the number of days for the given month and year.
     *
     * @param int $month Month to check
     * @param int $year  Year to check
     *
     * @return int
     */
    function number_days_in_month(int $month = 0, int $year = 0): int
    {
        if ($month < 1 or $month > 12) {
            return 0;
        }

        if (!is_numeric($year) or strlen((string)$year) != 4) {
            $year = date('Y');
        }

        if ($month == 2) {
            if ($year % 400 == 0 or ($year % 4 == 0 and $year % 100 != 0)) {
                return 29;
            }
        }

        $days_in_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        return $days_in_month[$month - 1];
    }
}

if (!function_exists('bytes2human')) {
    /**
     * Converts bytes to human readable size.
     *
     * @method bytesToHumanReadableSize
     *
     * @param int $size      Size in bytes
     * @param int $precision returned value precision
     *
     * @return string Human readable size
     */
    function bytes2human(int $size, int $precision = 2): string
    {
        for ($i = 0; ($size / 1024) > 0.9; $i++, $size /= 1024) {
        }

        return round($size, $precision) . ' ' . ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'][$i];
    }
}

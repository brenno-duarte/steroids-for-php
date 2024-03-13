<?php

declare(strict_types=1);

if (!function_exists('is_valid_utf8')) {
    /**
     * Check if the given value is a valid UTF-8 string
     *
     * @param string $value
     * 
     * @return bool
     */
    function is_valid_utf8(string $value): bool
    {
        $cur_encoding = mb_detect_encoding($value);

        if ($cur_encoding == "UTF-8" && mb_check_encoding($value, "UTF-8")) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('array_encode_utf8')) {
    /**
     * Convert array to UTF-8
     *
     * @param array $array
     * @param string $source_encoding
     *
     * @return array
     */
    function array_encode_utf8(array $array, string $source_encoding): array
    {
        array_walk_recursive(
            $array,
            function ($array) use ($source_encoding) {
                $array = mb_convert_encoding($array, 'UTF-8', $source_encoding);
            }
        );

        return $array;
    }
}

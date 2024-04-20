<?php

if (!function_exists('htmlspecialchars_recursive')) {
    /**
     * Convert special characters to HTML entities
     *
     * @param mixed $args
     * 
     * @return mixed
     */
    function htmlspecialchars_recursive(mixed $args): mixed
    {
        foreach ($args as $key => $value) {
            if (is_array($args)) {
                if (array_key_exists($key, $args)) {
                    unset($args[$key]);
                }
            }
        }

        if (is_array($args)) {
            return array_map(htmlspecialchars_recursive(...), $args);
        }

        if (is_scalar($args)) {
            return htmlspecialchars($args, ENT_COMPAT | ENT_HTML401, 'UTF-8', false);
        }

        return $args;
    }
}

if (!function_exists('wrap_implode')) {
    /**
     * Add a string before or after
     *
     * @param array $array
     * @param string $before
     * @param string $after
     * @param string $separator
     * 
     * @return string
     */
    function wrap_implode(array $array, string $before = '', string $after = '', string $separator = ''): string
    {
        return $before . implode("{$after}{$separator}{$before}", $array) . $after;
    }
}

if (!function_exists('mapped_implode')) {
    /**
     * implode an array as key-value pairs
     *
     * @param string $glue
     * @param array $array
     * @param string $symbol
     * 
     * @return string
     */
    function mapped_implode(string $glue, array $array, string $symbol = '='): string
    {
        $map = array_map(
            function ($k, $v) use ($symbol) {
                return $k . $symbol . $v;
            },
            array_keys($array),
            array_values($array)
        );

        return implode($glue, $map);
    }
}

if (!function_exists('implode_recursive')) {
    /**
     * Join pieces with a string recursively.
     * 
     * @param mixed $separator String between pairs(glue) or an array pair's glue and key/value glue or $pieces.
     * @param iterable $pieces Pieces to implode (optional).
     * @return string Joined string
     */
    function implode_recursive(iterable|string $separator, iterable $pieces = null): string
    {
        $separator2 = null;
        $result = [];

        if ($pieces === null) {
            $pieces = $separator;
            $separator = '';
        } elseif (is_iterable($separator)) {
            $separator = (array)$separator;
            list($separator, $separator2) = $separator;
        }

        foreach ($pieces as $key => $value) {
            $result[] = $separator2 === null ? $value : $key . $separator2 . $value;
        }

        return implode($separator, $result);
    }
}

if (!function_exists('strpos_recursive')) {
    /**
     * Find the position of the first occurrence of a substring in a string
     *
     * @param string $haystack
     * @param string $needle
     * @param int $offset
     * @param array $results
     * 
     * @return string|array
     */
    function strpos_recursive(string $haystack, string $needle, int $offset = 0, array $results = []): string|array
    {
        $offset = strpos($haystack, $needle, $offset);

        if ($offset === false) {
            return $results;
        } else {
            $results[] = $offset;
            return strpos_recursive($haystack, $needle, ($offset + 1), $results);
        }
    }
}

<?php

declare(strict_types=1);

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
                if (array_key_exists($key, $args)) unset($args[$key]);
            }
        }

        if (is_array($args)) return array_map(htmlspecialchars_recursive(...), $args);
        if (is_scalar($args)) return htmlspecialchars($args, ENT_COMPAT | ENT_HTML401, 'UTF-8', false);
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
     * @param null|iterable $pieces Pieces to implode (optional).
     * @return string Joined string
     */
    function implode_recursive(iterable|string $separator, ?iterable $pieces = null): string
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
    function strpos_recursive(
        string $haystack, 
        string $needle, 
        int $offset = 0, 
        array $results = []
    ): string|array {
        $offset = strpos($haystack, $needle, $offset);

        if ($offset === false) {
            return $results;
        } else {
            $results[] = $offset;
            
            return strpos_recursive(
                $haystack, 
                $needle, 
                ($offset + 1), 
                $results
            );
        }
    }
}

if (!function_exists('str_before')) {
    /**
     * Get the string before the first occurence of the substring.
     * If the substring is not found, the whole string is returned.
     *
     * @param string $string
     * @param string $substr
     * @return string
     */
    function str_before(string $string, string $substr): string
    {
        $pos = strpos($string, $substr);
        return $pos === false ? $string : substr($string, 0, $pos);
    }
}

if (!function_exists('str_after')) {
    /**
     * Get the string after the first occurence of the substring.
     * If the substring is not found, an empty string is returned.
     *
     * @param string $string
     * @param string $substr
     * @return string
     */
    function str_after(string $string, string $substr): string
    {
        $pos = strpos($string, $substr);
        return $pos === false ? '' : substr($string, $pos + strlen($substr));
    }
}

if (!function_exists('str_remove_accents')) {
    /**
     * Replace characters with accents with normal characters.
     *
     * @param string $string
     * @return string
     */
    function str_remove_accents(string $string): string
    {
        $from = [
            'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î',
            'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß',
            'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î',
            'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā',
            'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď',
            'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ',
            'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ',
            'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ',
            'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ',
            'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ',
            'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ',
            'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż',
            'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ',
            'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ',
            'ǿ'
        ];

        $to = [
            'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I',
            'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's',
            'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i',
            'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A',
            'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd',
            'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G',
            'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i',
            'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L',
            'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O',
            'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's',
            'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U',
            'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z',
            'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o',
            'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O',
            'o'
        ];

        return str_replace($from, $to, $string);
    }
}

if (!function_exists('str_slug')) {
    /**
     * Generate a URL friendly slug from the given string.
     *
     * @param string $string
     * @param string $glue
     * @return string
     */
    function str_slug(string $string, string $glue = '-'): string
    {
        $normalized = str_remove_accents($string);
        $lower = strtolower($normalized);

        return preg_replace('/[\W_]+/', $glue, $lower);
    }
}

if (!function_exists('str_shorten')) {
    /**
     * Truncate String (shorten) with or without ellipsis.
     *
     * @param string $string      String to truncate
     * @param int    $maxLength   Maximum length of string
     * @param bool   $addEllipsis if True, "..." is added in the end of the string, default true
     * @param bool   $wordsafe    if True, Words will not be cut in the middle
     *
     * @return string Shortened Text
     */
    function str_shorten(
        string $string,
        int $maxLength,
        bool $addEllipsis = true,
        bool $wordsafe = false
    ): string {
        $ellipsis = '';
        $maxLength = max($maxLength, 0);

        if (mb_strlen($string) <= $maxLength) return $string;

        if ($addEllipsis) {
            $ellipsis = mb_substr('...', 0, $maxLength);
            $maxLength -= mb_strlen($ellipsis);
            $maxLength = max($maxLength, 0);
        }

        $string = mb_substr($string, 0, $maxLength);

        if ($wordsafe) {
            $string = preg_replace(
                '/\s+?(\S+)?$/', 
                '', 
                mb_substr($string, 0, $maxLength)
            );
        }

        if ($addEllipsis) $string .= $ellipsis;

        return $string;
    }
}

if (!function_exists("str_icontains")) {
    /**
     * Determine if a string contains a given substring in case-insensitive
     *
     * @param string $haystack
     * @param string $needle
     * 
     * @return bool
     */
    function str_icontains(string $haystack, string $needle): bool
    {
        return $needle !== '' && mb_stripos($haystack, $needle) !== false;
    }
}
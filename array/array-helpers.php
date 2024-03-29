<?php

if (!function_exists('array_flatten')) {
    /**
     * Recursively reduces deep arrays to single-dimensional arrays
     *
     * @param array $array
     * @param int $preserve_keys (0=>never, 1=>strings, 2=>always)
     * @param array $new_array
     * 
     * @return array
     */
    function array_flatten(array $array, int $preserve_keys = 1, array $new_array = []): array
    {
        foreach ($array as $key => $child) {
            if (is_array($child)) {
                $new_array = array_flatten($child, $preserve_keys, $new_array);
            } elseif ($preserve_keys + is_string($key) > 1) {
                $new_array[$key] = $child;
            } else {
                $new_array[] = $child;
            }
        }

        return $new_array;
    }
}

if (!function_exists('array_delete')) {
    /**
     * This function simplifies removal of a value from an array, when the index is not known
     * 
     * @see https://wiki.php.net/rfc/array_delete
     *
     * @param array $array
     * @param string $value
     * @param bool $strict
     * 
     * @return array
     */
    function array_delete(array $array, string $value, bool $strict = true): array
    {
        if ($strict) {
            foreach ($array as $key => $item) {
                if ($item === $value) {
                    unset($array[$key]);
                }
            }
        } else {
            foreach ($array as $key => $item) {
                if ($item == $value) {
                    unset($array[$key]);
                }
            }
        }

        return $array;
    }
}

if (!function_exists('array_key_delete')) {
    /**
     * Delete the given key or index in the array
     *
     * @param array $array
     * @param mixed $key
     * @param bool $strict
     * 
     * @return array
     */
    function array_key_delete(array $array, mixed $key, bool $strict = true): array
    {
        if ($strict) {
            foreach ($array as $array_key => $item) {
                if ($array_key === $key) {
                    unset($array[$key]);
                }
            }
        } else {
            foreach ($array as $array_key => $item) {
                if ($array_key == $key) {
                    unset($array[$key]);
                }
            }
        }

        return $array;
    }
}

if (!function_exists('array_find')) {
    /**
     * If you only know a part of a value in an array and want to know the complete value
     *
     * @param string $needle
     * @param array $haystack
     * 
     * @return mixed
     */
    function array_find(string $needle, array $haystack): mixed
    {
        foreach ($haystack as $item) {
            if (str_contains($item, $needle)) {
                return $item;
                break;
            }
        }
    }
}

if (!function_exists('array_group')) {
    /**
     * Flip an array and group the elements by value
     *
     * @param array $array
     * 
     * @return array
     */
    function array_group(array $array): array
    {
        $outArr = [];

        array_walk($array, function ($value, $key) use (&$outArr) {
            if (!isset($outArr[$value]) || !is_array($outArr[$value])) {
                $outArr[$value] = [];
            }

            $outArr[$value][] = $key;
        });

        return $outArr;
    }
}

if (!function_exists('array_add')) {
    /**
     * Add an value to array
     * 
     * @see https://wiki.php.net/rfc/array_delete
     *
     * @param array $array
     * @param string $value
     * @param bool $strict
     * 
     * @return array|false
     */
    function array_add(array $array, mixed $value, bool $strict = true): array|false
    {
        if (false === array_search($value, $array, $strict)) {
            if (is_array($value)) {
                $array = array_merge($array, $value);
            } else {
                $array[] = $value;
            }

            return $array;
        }

        return false;
    }
}

if (!function_exists('array_identical_values')) {
    /**
     * If two arrays' values are exactly the same (regardless of keys and order)
     *
     * @param array $array1
     * @param array $array2
     * 
     * @return bool
     */
    function array_identical_values(array $array1, array $array2): bool
    {
        sort($array1);
        sort($array2);
        return $array1 == $array2;
    }
}

if (!function_exists('array_preg_diff')) {
    /**
     * List all the files and folders you want to exclude in a project directory
     *
     * @param mixed $needle
     * @param mixed $pattern
     * 
     * @return string
     */
    function array_preg_diff(string $needle, string $pattern): string
    {
        foreach ($needle as $i => $v) {
            if (preg_match($pattern, $v)) {
                unset($needle[$i]);
            }
        }

        return $needle;
    }
}

/**
 * array_first() and array_last() will return a tuple containing the
 * key and value of either the first or last items in an array respectively.
 * 
 * This is a polyfill for array_value_first() and array_value_last() which
 * were not accepted alongside array_key_first() and array_key_last() in
 * the RFC vote for them. These functions combine the two sets into one and
 * will work with PHP <= 7.2.1.
 */
if (!function_exists('array_first')) {
    /**
     * Returns a tuple containing the first key and value from an array.
     * 
     * @param array $array
     * @return null|array<int|string,mixed>
     */
    function array_first(array $array): mixed
    {
        if (!is_array($array) || empty($array)) {
            return null;
        }

        reset($array);
        $key = key($array);
        return array($key, $array[$key]);
    }
}

if (!function_exists('array_last')) {
    /**
     * Returns a tuple containing the last key and value from an array.
     * 
     * @param array $array
     * @return null|array<int|string,mixed>
     */
    function array_last(array $array): mixed
    {
        if (!is_array($array) || empty($array)) {
            return null;
        }

        end($array);
        $key = key($array);
        return array($key, $array[$key]);
    }
}

if (!function_exists('array_value_first')) {
    /**
     * Returns the first value from an array.
     * 
     * @return null|mixed
     */
    function array_value_first(array $array): mixed
    {
        $value = array_first($array);

        if (!$value) {
            return null;
        }

        return $value[1];
    }
}

if (!function_exists('array_value_last')) {
    /**
     * Returns the last value from an array.
     * 
     * @return null|mixed
     */
    function array_value_last(array $array): mixed
    {
        $value = array_last($array);

        if (!$value) {
            return null;
        }

        return $value[1];
    }
}

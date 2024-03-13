<?php

if (!function_exists('array_search_recursive')) {
    /**
     * Searches the recursive array for a given value and returns the first corresponding array value if successful
     *
     * @param array $needle
     * @param array $haystack
     * 
     * @return array
     */
    function array_search_recursive(array $needle, array $haystack): array
    {
        foreach ($haystack as $item) {
            $is_match = true;

            foreach ($needle as $key => $value) {
                if (is_object($item)) {
                    if (!isset($item->$key)) {
                        $is_match = false;
                        break;
                    }
                } else {
                    if (!isset($item[$key])) {
                        $is_match = false;
                        break;
                    }
                }

                if (is_object($item)) {
                    if ($item->$key != $value) {
                        $is_match = false;
                        break;
                    }
                } else {
                    if ($item[$key] != $value) {
                        $is_match = false;
                        break;
                    }
                }
            }

            if ($is_match) {
                return $item;
            }
        }

        return false;
    }
}

if (!function_exists('array_values_recursive')) {
    /**
     * Get all values from specific key in a multidimensional array
     *
     * @param string $key
     * @param array $arr
     * 
     * @return mixed
     */
    function array_values_recursive(string $key, array $arr): mixed
    {
        $val = [];

        array_walk_recursive($arr, function ($v, $k) use ($key, &$val) {
            if ($k == $key) array_push($val, $v);
        });

        return count($val) > 1 ? $val : array_pop($val);
    }
}

if (!function_exists('array_shift_recursive')) {
    /**
     * Shift an element off the beginning of multidimensional array
     *
     * @param array $array
     * 
     * @return array
     * 
     */
    function array_shift_recursive(array $array): array
    {
        list($k) = array_keys($array);
        unset($array[$k]);
        return $array;
    }
}

if (!function_exists('array_unique_recursive')) {
    /**
     * Get duplicate and unique values from an array
     *
     * @param array $array
     * @param mixed $key
     * 
     * @return array
     */
    function array_unique_recursive(array $array, mixed $key): array
    {
        $uniq_array = [];
        $dup_array = [];
        $key_array = [];

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[] = $val[$key];
                $uniq_array[] = $val;
            } else {
                $dup_array[] = $val;
            }
        }

        return [$uniq_array, $dup_array];
    }
}

if (!function_exists('array_keys_recursive')) {
    /**
     * Find all the keys from a multidimensional  array while keeping the array structure
     *
     * @param mixed $array
     * @param  $max_depth An optional MAXIMUM DEPTH parameter can be set for testing purpose in case of very large arrays
     * @param int $depth
     * @param array $array_keys
     * 
     * @return array
     * 
     */
    function array_keys_recursive($array, $max_depth = INF, $depth = 0, $array_keys = []): array
    {
        if ($depth < $max_depth) {
            $depth++;
            $keys = array_keys($array);

            foreach ($keys as $key) {
                if (is_array($array[$key])) {
                    $array_keys[$key] = array_keys_recursive($array[$key], $max_depth, $depth);
                }
            }
        }

        return $array_keys;
    }
}

if (!function_exists('in_array_recursive')) {
    /**
     * Recursive in_array function
     *
     * @param mixed $needle
     * @param array $haystack
     * @param bool $strict
     * 
     * @return bool
     */
    function in_array_recursive(mixed $needle, array $haystack, bool $strict): bool
    {
        foreach ($haystack as $element) {
            if ($element === $needle) {
                return true;
            }

            $isFound = false;

            if (is_array($element)) {
                $isFound = in_array($needle, $element, $strict);
            }

            if ($isFound === true) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('array_map_recursive')) {
    /**
     * Applies the callback to the elements of the given recursive arrays
     *
     * @param callable $callback
     * @param array $array
     * 
     * @return array
     */
    function array_map_recursive(callable $callback, array $array): array
    {
        array_walk_recursive($array, function (&$v) use ($callback) {
            $v = $callback($v);
        });

        return $array;
    }
}

if (!function_exists('array_sum_recursive')) {
    /**
     * Calculate the sum of values in an multidimensional array
     *
     * @param array $array
     * 
     * @return int|float
     */
    function array_sum_recursive(array $array): int|float
    {
        $sum = null;

        foreach ($array as $child) {
            $sum += is_array($child) ? array_sum_recursive($child) : $child;
        }

        return $sum;
    }
}

if (!function_exists('array_column_recursive')) {
    /**
     * array_column implementation that works on multidimensional arrays (not just 2-dimensional)
     *
     * @param array $haystack
     * @param mixed $needle
     * 
     * @return array
     */
    function array_column_recursive(array $haystack, mixed $needle): array
    {
        $found = [];

        array_walk_recursive($haystack, function ($value, $key) use (&$found, $needle) {
            if ($key == $needle)
                $found[] = $value;
        });

        return $found;
    }
}

if (!function_exists('array_push_recursive')) {
    /**
     * Push a multidimensional numeric array into another
     *
     * @param array $array1
     * @param array $array2
     * 
     * @return array
     */
    function array_push_recursive(array $array1, array $array2): array
    {
        $lastKey = array_key_last($array1);

        for ($i = 0; $i < count($array2); $i++) {
            $KeyPosition = 1 + $i;
            $array1[$lastKey + $KeyPosition] = $array2[$i];
        }

        return $array1;
    }
}

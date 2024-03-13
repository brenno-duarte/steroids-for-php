<?php

if (!function_exists('array_map_assoc')) {
    /**
     * A general solution for the problem of wanting to know the keys in the callback, 
     * and/or retain the key association in the returned array
     *
     * @param callable $callback
     * @param array $array
     * @param array ...$arrays
     * 
     * @return array
     * 
     */
    function array_map_assoc(callable $callback, array $array, array ...$arrays): array
    {
        $keys = array_keys($array);
        array_unshift($arrays, $keys, $array);
        return array_combine($keys, array_map($callback, ...$arrays));
    }
}

if (!function_exists('array_slice_assoc')) {
    /**
     * Array slice function that works with associative arrays (keys)
     *
     * @param array $array
     * @param array $keys
     * 
     * @return array
     */
    function array_slice_assoc(array $array, array $keys): array
    {
        return array_intersect_key($array, array_flip($keys));
    }
}

if (!function_exists('array_slice_assoc_inverse')) {
    /**
     * Inverse associative version of array_slice_assoc() function
     *
     * @param array $array
     * @param array $keys
     * 
     * @return array
     */
    function array_slice_assoc_inverse(array $array, array $keys): array
    {
        return array_diff_key($array, array_flip($keys));
    }
}

if (!function_exists('array_unshift_assoc')) {
    /**
     * Prepend one or more elements to the beginning of an array
     *
     * @param array $array
     * @param mixed $key
     * @param mixed $val
     * 
     * @return array
     */
    function array_unshift_assoc(array $array, mixed $key, mixed $val): array
    {
        $array = array_reverse($array, true);
        $array[$key] = $val;
        $array = array_reverse($array, true);
        return $array;
    }
}

if (!function_exists('array_splice_assoc')) {
    /**
     * Remove a portion of the associative array and replace it with something else
     *
     * @param array $array
     * @param mixed $offset
     * @param mixed $length
     * @param mixed $replacement
     * 
     * @return array
     * 
     */
    function array_splice_assoc(array $array, mixed $offset, mixed $length, mixed $replacement): array
    {
        $replacement = (array) $replacement;
        $key_indices = array_flip(array_keys($array));

        if (isset($array[$offset]) && is_string($offset)) {
            $offset = $key_indices[$offset];
        }

        if (isset($array[$length]) && is_string($length)) {
            $length = $key_indices[$length] - $offset;
        }

        $array = array_slice($array, 0, $offset, TRUE)
            + $replacement
            + array_slice($array, $offset + $length, NULL, TRUE);

        return $array;
    }
}

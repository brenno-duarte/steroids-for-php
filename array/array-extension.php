<?php

if (!function_exists('array_many_keys_exists')) {
    /**
     * Checks if multiple keys exist in an array
     *
     * @param array $array
     * @param array|string $keys
     *
     * @return bool
     */
    function array_many_keys_exists(array $array, array|string $keys): bool
    {
        $count = 0;

        if (!is_array($keys)) {
            $keys = func_get_args();
            array_shift($keys);
        }

        foreach ($keys as $key) {
            if (isset($array[$key]) || array_key_exists($key, $array)) {
                $count++;
            }
        }

        return count($keys) === $count;
    }
}

if (!function_exists('array_value_first')) {
    /**
     * Get the first value of array
     *
     * @param object|array $array
     * 
     * @return mixed
     */
    function array_value_first(object|array $array): mixed
    {
        $first_element = (new ArrayIterator($array))->current();
        return $first_element;
    }
}

if (!function_exists('array_value_empty')) {
    /**
     * Checks if all of given array's elements have a non-falsy value
     * 
     * @param array $array
     * 
     * @return bool
     */
    function array_value_empty($array): bool
    {
        $array_count    = count($array);
        $filtered_count = count(array_filter($array));

        return ($array_count === $filtered_count) ? true : false;
    }
}

if (!function_exists('array_value_count')) {
    /**
     * Count the total number of times a specific value appears in array
     *
     * @param mixed $match
     * @param array $array
     * 
     * @return int
     */
    function array_value_count(mixed $match, array $array): int
    {
        $count = 0;

        foreach ($array as $key => $value) {
            if ($value == $match) {
                $count++;
            }
        }

        return $count;
    }
}

if (!function_exists('array_column_group_key')) {
    /**
     * Group values by the same `index_key` in arrays one
     *
     * @param array $array
     * @param int|string $column_key
     * @param int|string $index_key
     * 
     * @return array
     */
    function array_column_group_key(array $array, int|string $column_key, int|string $index_key): array
    {
        $output = [];
        foreach ($array as $item) {
            $output[$item[$index_key]][] = $item[$column_key];
        }

        return $output;
    }
}

if (!function_exists('array_search_with_keys')) {
    /**
     * Get all key=>value associations of an array with the given search-value
     *
     * @param mixed $needle
     * @param mixed $haystack
     * @param bool $strict
     * 
     * @return array
     */
    function array_search_with_keys(mixed $needle, mixed $haystack, bool $strict = false): array
    {
        return array_intersect_key($haystack, array_flip(array_keys($haystack, $needle, $strict)));
    }
}

if (!function_exists('array_push_before')) {
    /**
     * Add elements to an array before a specific index or key
     * 
     * @return array
     * @param array $src
     * @param array $in
     * @param int|string $pos
     * 
     * @return array
     */
    function array_push_before(array $src, array $in, int|string $pos): array
    {
        $R = [];

        if (is_int($pos)) {
            $R = array_merge(array_slice($src, 0, $pos), $in, array_slice($src, $pos));
        } else {
            foreach ($src as $k => $v) {
                if ($k == $pos) {
                    $R = array_merge($R, $in);
                }

                $R[$k] = $v;
            }
        }

        return $R;
    }
}

if (!function_exists('array_push_after')) {
    /**
     * Add elements to an array after a specific index or key
     * 
     * @return array
     * @param array $src
     * @param array $in
     * @param int|string $pos
     * 
     * @return array
     */
    function array_push_after(array $src, array $in, int|string $pos): array
    {
        $R = [];

        if (is_int($pos)) {
            $R = array_merge(array_slice($src, 0, $pos + 1), $in, array_slice($src, $pos + 1));
        } else {
            foreach ($src as $k => $v) {
                $R[$k] = $v;

                if ($k == $pos) {
                    $R = array_merge($R, $in);
                }
            }
        }

        return $R;
    }
}

if (!function_exists('array_push_array')) {
    /**
     * A function which mimics push() from perl, perl lets you push an array to an array
     *
     * @param array $array
     * 
     * @return array
     */
    function array_push_array(array &$array): array
    {
        $numArgs = func_num_args();

        if (2 > $numArgs) {
            trigger_error(sprintf('%s: expects at least 2 parameters, %s given', __FUNCTION__, $numArgs), E_USER_WARNING);
            return false;
        }

        $values = func_get_args();
        array_shift($values);

        foreach ($values as $v) {
            if (is_array($v)) {
                if (count($v) > 0) {
                    foreach ($v as $w) {
                        $array[] = $w;
                    }
                }
            } else {
                $array[] = $v;
            }
        }

        return $array;
    }
}

if (!function_exists('array_combine_identical_keys')) {
    /**
     * Preserve duplicate keys when combining arrays
     *
     * @param array $keys
     * @param array $values
     * 
     * @return array
     */
    function array_combine_identical_keys(array $keys, array $values): array
    {
        $result = array();

        foreach ($keys as $i => $k) {
            $result[$k][] = $values[$i];
        }

        array_walk($result, function (&$v) {
            $v = (count($v) == 1) ? array_pop($v) : $v;
        });

        return $result;
    }
}

if (!function_exists('array_combine_different_size')) {
    /**
     * Creates an array by using one array for keys and another for its values if they are not of same size
     *
     * @param array $a
     * @param array $b
     * 
     * @return array
     */
    function array_combine_different_size(array $a, array $b): array
    {
        $acount = count($a);
        $bcount = count($b);
        $size = ($acount > $bcount) ? $bcount : $acount;
        $a = array_slice($a, 0, $size);
        $b = array_slice($b, 0, $size);
        return array_combine($a, $b);
    }
}

if (!function_exists('array_splice_preserve_keys')) {
    /**
     * array_splice with preserve keys
     *
     * @param array $array
     * @param int $offset
     * @param int|null $length
     * 
     * @return array
     */
    function array_splice_preserve_keys(array $array, int $offset, ?int $length = null): array
    {
        $result = array_slice($array, $offset, $length, true);
        $array = array_slice($array, $offset + $length, null, true);

        return $result;
    }
}

if (!function_exists('array_change_key_case_unicode')) {
    /**
     * Changes the case of all keys in an Unicode array
     *
     * @param array $arr
     * @param int $c
     * 
     * @return array
     */
    function array_change_key_case_unicode(array $arr, int $c = CASE_LOWER): array
    {
        foreach ($arr as $k => $v) {
            $ret[mb_convert_case($k, (($c === CASE_LOWER) ? MB_CASE_LOWER : MB_CASE_UPPER), "UTF-8")] = (is_array($v) ? array_change_key_case_unicode($v, $c) : $v);
        }

        return $ret;
    }
}

if (!function_exists('array_change_value_case')) {
    /**
     * Returns an array with all values lowercased or uppercased.
     * 
     * @return array Returns an array with all values lowercased or uppercased.
     * @param array $array The array to work on 
     * @param int $case [optional] Either \c CASE_UPPER or \c CASE_LOWER (default).
     * 
     * @return array
     */
    function array_change_value_case(array $array, int $case = CASE_LOWER): array
    {
        switch ($case) {
            case CASE_LOWER:
                return array_map('strtolower', $array);
                break;

            case CASE_UPPER:
                return array_map('strtoupper', $array);
                break;

            default:
                trigger_error('Case is not valid, CASE_LOWER or CASE_UPPER only', E_USER_ERROR);
                return false;
        }
    }
}
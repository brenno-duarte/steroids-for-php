<?php

if (!function_exists('array_flatten')) {
    /**
     * Flatten a nested associative array, concatenating the keys.
     *
     * @param array  $array
     * @param string $glue
     * @return array
     */
    function array_flatten(array $array, string $glue = '.'): array
    {
        foreach ($array as $key => &$value) {
            if (!is_associative_array($value)) continue;

            unset($array[$key]);
            $value = array_flatten($value, $glue);

            foreach ($value as $subkey => $subvalue) {
                $array[$key . $glue . $subkey] = $subvalue;
            }
        }

        return $array;
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
                if ($item === $value) unset($array[$key]);
            }
        } else {
            foreach ($array as $key => $item) {
                if ($item == $value) unset($array[$key]);
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
                if ($array_key === $key) unset($array[$key]);
            }
        } else {
            foreach ($array as $array_key => $item) {
                if ($array_key == $key) unset($array[$key]);
            }
        }

        return $array;
    }
}

if (!function_exists('array_find_key')) {
    /**
     * Find a key of an array using a callback function.
     * @see array_filter()
     *
     * Returns the key or FALSE if no element was found.
     *
     * @param array    $array
     * @param callable $callback
     * @param int      $flag      Flag determining what arguments are sent to callback
     * @return string|int|false
     */
    function array_find_key(array $array, callable $callback, int $flag = 0): mixed
    {
        foreach ($array as $key => $value) {
            $args = $flag === ARRAY_FILTER_USE_BOTH ? 
                [$value, $key] : 
                ($flag === ARRAY_FILTER_USE_KEY ? [$key] : [$value]);

            if ($callback(...$args)) return $key;
        }

        return false;
    }
}

if (!function_exists('array_group')) {
    /**
     * This function takes an array and a function and returns an array that contains arrays - groups of consecutive elements.
     *
     * @param array $array
     * @param callable $callback
     * 
     * @return array
     * @see https://wiki.php.net/rfc/array_group
     */
    function array_group(array $array, callable $callback): array
    {
        $groups = [];
        $group = [];
        $prev = null;

        foreach ($array as $value) {
            if ($group && !$callback($prev, $value)) {
                $groups[] = $group;
                $group = [];
            }

            $group[] = $value;
            $prev = $value;
        }

        if ($group) $groups[] = $group;
        return $groups;
    }
}

if (!function_exists('array_add')) {
    /**
     * Add an value to array
     * 
     * @see https://wiki.php.net/rfc/array_delete
     *
     * @param array $array
     * @param mixed $value
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
     * @param array $needle
     * @param string $pattern
     * 
     * @return mixed
     */
    function array_preg_diff(array $needle, string $pattern): array
    {
        foreach ($needle as $i => $v) {
            if (preg_match($pattern, $v)) unset($needle[$i]);
        }

        return $needle;
    }
}

if (!function_exists('array_tree')) {
    /**
     * Display the hole structure (tree) of your array
     *
     * @param array $array
     * @param int $index
     * 
     * @return string
     */
    function array_tree(mixed $array, int $index = 0): ?string
    {
        $tree = null;
        $space = "";

        for ($i = 0; $i < $index; $i++) {
            $space .= "     ";
        }

        $index++;

        if (is_array($array)) {
            foreach ($array as $x => $tmp) {
                $tree .= $space . "$x => $tmp\n";
                array_tree($tmp, $index);
            }
        }

        return $tree;
    }
}

if (!function_exists('array_only')) {
    /**
     * Return an array with only the specified keys.
     *
     * @param array          $array
     * @param string[]|int[] $keys
     * @return array
     */
    function array_only(array $array, array $keys): array
    {
        $intersect = array_fill_keys($keys, null);
        return array_intersect_key($array, $intersect);
    }
}

if (!function_exists('array_without')) {
    /**
     * Return an array without the specified keys.
     *
     * @param array          $array
     * @param string[]|int[] $keys
     * @return array
     */
    function array_without(array $array, array $keys): array
    {
        $intersect = array_fill_keys($keys, null);
        return array_diff_key($array, $intersect);
    }
}

if (!function_exists('array_contains_all')) {
    /**
     * Check if an array contains all values in a set.
     *
     * @param array $array
     * @param array $subset
     * @param bool  $strict  Strict type checking
     * @return bool
     */
    function array_contains_all(array $array, array $subset, bool $strict = false): bool
    {
        $contains = true;

        foreach ($subset as $value) {
            if (!in_array($value, $array, $strict)) {
                $contains = false;
                break;
            }
        }

        return $contains;
    }
}

if (!function_exists('array_contains_all_assoc')) {
    /**
     * Check if an array contains all values in a set with index check.
     *
     * @param array $array
     * @param array $subset
     * @param bool  $strict  Strict type checking
     * @return bool
     */
    function array_contains_all_assoc(array $array, array $subset, bool $strict = false): bool
    {
        if (count(array_diff_key($subset, $array)) > 0) { // Quick test, just on keys
            return false;
        }

        $contains = true;

        foreach ($subset as $key => $value) {
            if (
                !array_key_exists($key, $array) ||
                isset($value) !== isset($array[$key]) ||
                ($strict ? $value !== $array[$key] : $value != $array[$key])
            ) {
                $contains = false;
            }
        }

        return $contains;
    }
}

if (!function_exists('array_contains_any')) {
    /**
     * Check if an array contains any value in a set.
     **
     * @param array $array
     * @param array $subset
     * @param bool  $strict  Strict type checking
     * @return bool
     */
    function array_contains_any(array $array, array $subset, bool $strict = false): bool
    {
        $contains = false;

        foreach ($subset as $value) {
            if (in_array($value, $array, $strict)) {
                $contains = true;
                break;
            }
        }

        return $contains;
    }
}

if (!function_exists('array_contains_any_assoc')) {
    /**
     * Check if an array contains any value in a set with index check.
     *
     * @param array $array
     * @param array $subset
     * @param bool  $strict  Strict type checking
     * @return bool
     */
    function array_contains_any_assoc(array $array, array $subset, bool $strict = false): bool
    {
        if (count(array_intersect_key($subset, $array)) === 0) { // Quick test, just on keys
            return false;
        }

        $contains = false;

        foreach ($subset as $key => $value) {
            if (
                array_key_exists($key, $array) &&
                isset($value) === isset($array[$key]) &&
                ($strict ? $value === $array[$key] : $value == $array[$key])
            ) {
                $contains = true;
                break;
            }
        }

        return $contains;
    }
}

if (!function_exists('array_join_pretty')) {
    /**
     * Join an array, using the 'and' parameter as glue the last two items.
     *
     * @param array  $array
     * @param string $glue
     * @param string $and
     * 
     * @return string
     */
    function array_join_pretty(array $array, string $glue, string $and): string
    {
        $last = (string)array_pop($array);
        return (count($array) === 0 ? "" : join($glue, $array) . $and) . $last;
    }
}

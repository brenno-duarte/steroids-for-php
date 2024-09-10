<?php

declare(strict_types=1);

if (!function_exists('is_set')) {
    /**
     * Checks whether variable names are set within the global space or they exists as an key and return if they are set (even if their values are null)
     * 
     * @param string $var_name name of the first variable to check
     * @param mixed
     *   optional array to check for key (if null, checks from $GLOBALS) OR
     *   other variable names to check OR
     *   other variable names and their associated arrays to their right (use null for global variables, optional if its the last parameter)
     */
    function is_set(string $var_name, mixed ...$args): bool
    {
        $vars[$var_name] = null;

        if (array_key_exists(0, $args)) {
            if (is_array($args[0])) {
                $vars[$var_name] = $args[0];
            } elseif (is_string($args[0])) {
                goto main;
            }

            unset($args[0]);
        }

        main:

        if ($args) {
            $args = array_reverse($args);
            $cur_array = null;

            array_walk($args, function ($value) use (&$cur_array, &$vars): void {
                if (!is_string($value)) {
                    $cur_array = $value;
                } else {
                    $vars[$value] = $cur_array;
                }
            });
        }

        foreach ($vars as $name => $array) {
            if (!array_key_exists($name, $array ?? $GLOBALS)) return false;
        }

        return true;
    }
}

if (!function_exists('is_associative_array')) {
    /**
     * Check if variable is an associative array.
     *
     * @param mixed $var
     * @return bool
     */
    function is_associative_array(mixed $var): bool
    {
        if (!is_array($var)) return false;
        $keys = array_keys($var);
        return ($keys !== array_keys($keys));
    }
}

if (!function_exists('is_numeric_array')) {
    /**
     * Check if variable is a numeric array.
     *
     * @param mixed $var
     * @return bool
     */
    function is_numeric_array(mixed $var): bool
    {
        if (!is_array($var)) return false;
        $keys = array_keys($var);
        return ($keys === array_keys($keys));
    }
}

if (!function_exists('is_stringable')) {
    /**
     * Check if variable is a string or can be cast to a string.
     *
     * @param mixed $var
     * @return bool
     */
    function is_stringable(mixed $var): bool
    {
        return (is_scalar($var) && !is_bool($var)) || (is_object($var) && method_exists($var, '__toString'));
    }
}

if (!function_exists('objectify')) {
    /**
     * Turn associated array into stdClass object recursively.
     *
     * @param mixed $var
     * @return \stdClass|mixed
     */
    function objectify(mixed $var): mixed
    {
        $i = func_num_args() > 1 ? func_get_arg(1) : 100;
        if ($i <= 0) throw new \OverflowException("Maximum recursion depth reached. Possible circular reference.");
        if (!is_array($var) && !is_object($var)) return $var;
        if (is_associative_array($var)) $var = (object)$var;

        foreach ($var as &$value) {
            $value = objectify($value, $i - 1);
        }

        return $var;
    }
}

if (!function_exists('arrayify')) {
    /**
     * Turn stdClass object into associated array recursively.
     *
     * @param \stdClass|mixed $var
     * @return array|mixed
     */
    function arrayify(mixed $var): mixed
    {
        $i = func_num_args() > 1 ? func_get_arg(1) : 100;
        if ($i <= 0) throw new \OverflowException("Maximum recursion depth reached. Possible circular reference.");
        if (!is_array($var) && !is_object($var)) return $var;
        if ($var instanceof \stdClass) $var = (array)$var;

        foreach ($var as &$value) {
            $value = arrayify($value, $i - 1);
        }

        return $var;
    }
}

if (!function_exists('expect_type')) {
    /**
     * Check that an argument has a specific type, otherwise throw an exception.
     *
     * @param mixed           $var
     * @param array|string $type
     * @param string          $throwable  Class name
     * @param string          $message
     * @return void
     * @throws \InvalidArgumentException
     */
    function expect_type(
        mixed $var,
        array|string $type,
        string $throwable = \TypeError::class,
        ?string $message = null
    ): void {
        $types = is_scalar($type) ? [$type] : $type;

        foreach ($types as &$curType) {
            if (str_ends_with($curType, ' resource')) {
                $valid = is_resource($var) && get_resource_type($var) === substr($curType, 0, -9);
            } else {
                $fn = $curType === 'boolean' ? 'is_bool' : 'is_' . $curType;
                $internal = function_exists($fn);

                $valid = $internal ? $fn($var) : is_a($var, $curType);
                $curType .= $internal ? '' : ' object';
            }

            if ($valid) {
                //$ok = $valid;
                return;
            }
        }

        //var_dump($ok);

        $varType = get_debug_type($var);
        if (is_array($type)) $type = implode(" or ", $type);
        $message = $message ?? 'Expected ' . $type . ', ' . $varType . ' given';

        throw new $throwable($message);
    }
}

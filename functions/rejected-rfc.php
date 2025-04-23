<?php

declare(strict_types=1);

/**
 * @see https://wiki.php.net/rfc/ifsetor
 *
 * @param mixed $variable
 * @param mixed|null $default
 * 
 * @return mixed
 */
function ifsetor(mixed $variable, mixed $default = null): mixed
{
    if (isset($variable)) {
        $tmp = $variable;
    } else {
        $tmp = $default;
    }

    return $tmp;
}

/**
 * Checks if a int|float value is within a certain bound
 * 
 * @see  https://wiki.php.net/rfc/clamp
 *
 * @param int|float $num
 * @param int|float $min
 * @param int|float $max
 * 
 * @return int|float
 */
function clamp(int|float $num, int|float $min, int|float $max): int|float
{
    if ($min > $max) throw new ValueError('clamp(): Argument #2 ($min) cannot be greater than Argument #3 ($max)');
    if ($num > $min && $num < $max) return $num;
    if ($num > $max) return $max;
    if ($num < $min) return $min;

    return 0;
}

if (!function_exists("safe_int")) {
    /**
     * Returns true if the value can be safely converted to an integer
     * 
     * @param mixed $val
     */
    function safe_int(mixed $val): bool
    {
        switch (gettype($val)) {
            case "integer":
                return true;
            case "double":
                return $val === (float)(int)$val;
            case "string":
                $losslessCast = (string)(int)$val;
                if ($val !== $losslessCast && $val !== "+$losslessCast") return false;
                return $val <= PHP_INT_MAX && $val >= PHP_INT_MIN;
            default:
                return false;
        }
    }
}

if (!function_exists("safe_float")) {
    /**
     * Returns true if the value can be safely converted to a float
     * 
     * @param mixed $val
     */
    function safe_float(mixed $val): bool
    {
        switch (gettype($val)) {
            case "double":
            case "integer":
                return true;
            case "string":
                // reject leading zeros unless they are followed by a decimal point
                if (strlen($val) > 1 && $val[0] === "0" && $val[1] !== ".") {
                    return false;
                }

                // Use regular expressions since FILTER_VALIDATE_FLOAT allows trailing whitespace
                // Based on http://php.net/manual/en/language.types.float.php
                $lnum    = "[0-9]+";
                $dnum    = "([0-9]*[\.]{$lnum})|({$lnum}[\.][0-9]*)";
                $expDnum = "/^[+-]?(({$lnum}|{$dnum})[eE][+-]?{$lnum})$/";

                return
                    preg_match("/^[+-]?{$lnum}$/", $val) ||
                    preg_match("/^[+-]?{$dnum}$/", $val) ||
                    preg_match($expDnum, $val);
            default:
                return false;
        }
    }
}

if (!function_exists("safe_string")) {
    /**
     * Returns true if the value can be safely converted to a string
     * 
     * @param mixed $val
     */
    function safe_string(mixed $val): bool
    {
        switch (gettype($val)) {
            case "string":
            case "integer":
            case "double":
                return true;
            case "object":
                return method_exists($val, "__toString");
            default:
                return false;
        }
    }
}

if (!function_exists("to_int")) {
    /**
     * Returns the value as an integer
     * 
     * @param mixed $val
     * @throws \Exception if the value cannot be safely cast to an integer
     */
    function to_int(mixed $val): int
    {
        if (!safe_int($val)) throw new \Exception("Value could not be converted to int");
        return (int)$val;
    }
}

if (!function_exists("to_float")) {
    /**
     * Returns the value as a float
     * 
     * @param mixed $val
     * @throws \Exception if the value cannot be safely cast to a float
     */
    function to_float(mixed $val): float
    {
        if (!safe_float($val)) throw new \Exception("Value could not be converted to float");
        return (float)$val;
    }
}

if (!function_exists("to_string")) {
    /**
     * Returns the value as a string
     * 
     * @param mixed $val
     * @throws \Exception if the value cannot be safely cast to a string
     */
    function to_string(mixed $val): string
    {
        if (!safe_string($val)) throw new \Exception("Value could not be converted to string");
        return (string)$val;
    }
}

if (!function_exists("var_info")) {
    /**
     * Helps developers to create better debug, error, and exception messages in their software
     *
     * @param mixed $var
     * 
     * @return string
     * @see https://wiki.php.net/rfc/var_info
     */
    function var_info(mixed $var): string
    {
        if (is_object($var) || $var instanceof \__PHP_Incomplete_Class) {
            return 'object of class ' . get_class($var);
        } elseif (is_resource($var)) {
            return 'resource of type ' . get_resource_type($var);
        } elseif (is_array($var)) {
            if (empty($var)) {
                return 'empty array';
            }
            if (is_callable($var)) {
                return 'callable array';
            };
            if (array_filter($var, 'is_int', ARRAY_FILTER_USE_KEY)) {
                return 'indexed array';
            }

            return 'associative array';
        } elseif (is_int($var)) {
            if ($var < 0) {
                return 'negative integer';
            }
            if ($var > 0) {
                return 'positive integer';
            }

            return 'zero integer';
        } elseif (is_float($var)) {
            if (is_infinite($var)) {
                return 'infinite float';
            }
            if (is_nan($var)) {
                return 'invalid float';
            }
            if ($var < 0) {
                return 'negative float';
            }
            if ($var > 0) {
                return 'positive float';
            }

            return 'zero float';
        } elseif (is_string($var)) {
            if (empty($var)) {
                return 'empty string';
            }
            if (is_callable($var)) {
                return 'callable string';
            }
            if (is_numeric($var)) {
                return 'numeric string';
            }

            return 'string';
        } elseif (is_bool($var)) {
            return true === $var ? 'boolean true' : 'boolean false';
        } else {
            return 'unknown type';
        }
    }
}

if (!function_exists('iterator_any')) {
    /**
     * Determines whether any element of the iterable satisfies the predicate.
     *
     *
     * If the value returned by the callback is truthy
     * (e.g. true, non-zero number, non-empty array, truthy object, etc.),
     * this is treated as satisfying the predicate.
     *
     * @param iterable $input
     * @param null|callable(mixed):mixed $callback
     * @return bool
     * 
     * @see https://wiki.php.net/rfc/any_all_on_iterable
     */
    function iterator_any(iterable $input, ?callable $callback = null): bool
    {
        foreach ($input as $v) {
            if ($callback !== null ? $callback($v) : $v) return true;
        }
        
        return false;
    }
}

if (!function_exists('iterator_all')) {
    /**
     * Determines whether all elements of the iterable satisfy the predicate.
     *
     * If the value returned by the callback is truthy
     * (e.g. true, non-zero number, non-empty array, truthy object, etc.),
     * this is treated as satisfying the predicate.
     *
     * @param iterable $input
     * @param null|callable(mixed):mixed $callback
     * @return bool
     * 
     * @see https://wiki.php.net/rfc/any_all_on_iterable
     */
    function iterator_all(iterable $input, ?callable $callback = null): bool
    {
        foreach ($input as $v) {
            if (!($callback !== null ? $callback($v) : $v)) return false;
        }
        
        return true;
    }
}
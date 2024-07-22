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
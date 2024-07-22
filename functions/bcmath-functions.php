<?php

declare(strict_types=1);

if (!function_exists('bcfact')) {
    /**
     * Calculates a factorial of given number
     * 
     * @param string $num
     * @throws InvalidArgumentException
     * @return string
     */
    function bcfact(string $num): string
    {
        for ($result = '1'; $num > 0; $num--) {
            $result = bcmul($result, (string)$num);
        }

        return $result;
    }
}

if (!function_exists('bcnegative')) {
    /**
     * Checks if a string number is negative starting from its first position
     *
     * @param string $number
     * 
     * @return bool
     */
    function bcnegative(string $number): bool
    {
        // To be negative it must have a hyphen in its first position
        return str_contains($number, '-');
    }
}

if (!function_exists('bcisdecimal')) {
    /**
     * Check if a string number is decimal or integer
     *
     * @param string $number
     * 
     * @return bool
     */
    function bcisdecimal(string $number): bool
    {
        // To be a decimal it must have the point
        return str_contains($number, '.');
    }
}

if (!function_exists('bcround')) {
    /**
     * Round a number from lib bcmath
     *
     * Explanation:
     * $number_example = "320.1357"
     * 1) Create a "$fix" number that will end up being of the form:
     *      - 0.005 for in, a scale of 2
     *      - 0.0005 for in, a scale of 3
     * 2) Then, add (or subtract) the value of $fix to the number you want to round, using bcadd (or bcsub) with a scale increased by 1:
     *      - 320.1357 + 0.005 for in, a scale of 2 -> = 320.1407
     *      - 320.1357 + 0.0005 for in, a scale of 3 -> = 320.1362
     * 3) Finally, trim the decimal part of the number to the number of digits indicated by $scale
     *      - 320.14 for a scale of 2
     *      - 320.136 for a scale of 3
     * Also: check that the number is decimal (and not integer)
     *
     * @param string $number
     * @param int $scale
     * 
     * @return string
     */
    function bcround(string $number, int $scale = 0): string
    {
        // If the number has a decimal part, it is processed
        if (bcisdecimal($number)) {
            // Calculate the $fix to add the remaining value to round
            $fix = '0.' . str_repeat('0', $scale) . '5';
            // If negative, subtract the fix
            if (bcnegative($number)) {
                $number = bcsub($number, $fix, $scale + 1);
            } else { // If it is positive, add the fix
                $number = bcadd($number, $fix, $scale + 1);
            }
            // Trim the decimal part to the number of $scale positions
            list($int, $decimal) = explode('.', $number);
            $decimal = substr($decimal, 0, $scale);

            return (!empty($decimal)) ? "$int.$decimal" : "$int";
        }

        // If the number is an integer, it returns the same
        return $number;
    }
}

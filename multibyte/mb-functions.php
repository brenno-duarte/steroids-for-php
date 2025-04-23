<?php declare(strict_types=1);

if (!function_exists('mb_substr_replace')) {
    /**
     * Replace text within a portion of a string
     *
     * @param array|string $string
     * @param array|string $replace
     * @param array|int $start
     * @param array|int|null $length
     *
     * @return array|string
     */
    function mb_substr_replace(array|string $string, array|string $replace, array|int $start, mixed $length = null): array|string
    {
        return mb_substr($string, 0, $start) . $replace . mb_substr($string, $start + $length);
    }
}

if (!function_exists('mb_str_contains')) {
    /**
     * Checks if $needle is found in $haystack and returns a boolean value (true/false) whether or not the $needle was found.
     *
     * @param string $haystack
     * @param string $needle
     * @param string|null $encoding
     *
     * @return bool
     */
    function mb_str_contains(string $haystack, string $needle, ?string $encoding = null): bool
    {
        return $needle === '' || mb_substr_count($haystack, $needle, (empty($encoding) ? mb_internal_encoding() : $encoding)) > 0;
    }
}

if (!function_exists('mb_ucwords')) {
    /**
     * Uppercase the first character of each word in a string.
     *
     * @param string $string   The input string to modify
     * @param string $encoding [optional] The character encoding. Defaults to 'UTF-8'.
     *
     * @return string The modified string
     */
    function mb_ucwords(string $string, string $encoding = 'UTF-8'): string
    {
        $result = '';
        $previous_character = ' ';

        $length = mb_strlen($string, $encoding);

        for ($i = 0; $i < $length; ++$i) {
            $current_character = mb_substr($string, $i, 1, $encoding);

            if (' ' === $previous_character)
                $current_character = mb_strtoupper($current_character, $encoding);

            $result .= $current_character;
            $previous_character = $current_character;
        }

        return $result;
    }
}

if (!function_exists('mb_ucfirst')) {
    /**
     * Make the first character of a string uppercase.
     *
     * @param string $string   The input string
     * @param string $encoding [optional] The character encoding. Defaults to 'UTF-8'.
     *
     * @return string The resulting string
     */
    function mb_ucfirst(string $string, string $encoding = 'UTF-8'): string
    {
        $first_char = mb_substr($string, 0, 1, $encoding);
        $rest = mb_substr($string, 1, null, $encoding);

        $lower_first_char = mb_strtolower($first_char, $encoding);

        if ($first_char === $lower_first_char)
            $first_char = mb_strtoupper($first_char, $encoding);

        return $first_char . $rest;
    }
}

if (!function_exists('mb_lcfirst')) {
    /**
     * Make a string's first character lowercase
     *
     * @param string $string
     *
     * @return string
     */
    function mb_lcfirst(string $string): string
    {
        return mb_strtolower(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }
}

if (!function_exists('mb_strrev')) {
    /**
     * Reverse a string
     *
     * @param string $string   The string to be reversed
     * @param string $encoding [optional] The character encoding. Defaults to 'UTF-8'.
     *
     * @return string the reversed string
     */
    function mb_strrev(string $string, string $encoding = 'UTF-8'): string
    {
        $length = mb_strlen($string, $encoding);
        $reversed = '';

        while ($length-- > 0) {
            $reversed .= mb_substr($string, $length, 1, $encoding);
        }

        return $reversed;
    }
}

if (!function_exists('mb_str_pad')) {
    /**
     * Pad a string to a certain length with another string
     *
     * @param string $input      The string to pad
     * @param int    $pad_length The length of the resulting padded string
     * @param string $pad_string [optional] The string to use for padding, defaults to ' '
     *                           The pad_string may be truncated if the required number of padding
     *                           characters can't be evenly divided by the pad_string's length
     * @param int    $pad_type   [optional] The type of padding to apply, defaults to STR_PAD_RIGHT
     *                           Can be STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH
     * @param string $encoding   [optional] The character encoding. Defaults to 'UTF-8'
     *
     * @return string The padded string
     *
     * @throws ValueError If $pad_type is not STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH
     */
    function mb_str_pad(
        string $input,
        int $pad_length,
        string $pad_string = ' ',
        int $pad_type = \STR_PAD_RIGHT,
        string $encoding = 'UTF-8'
    ): string {
        if (!in_array(
            $pad_type,
            [\STR_PAD_RIGHT, \STR_PAD_LEFT, \STR_PAD_BOTH],
            true
        )) {
            throw new ValueError('Argument #4 ($pad_type) must be STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH');
        }

        return str_pad(
            $input, 
            strlen($input) - mb_strlen($input, $encoding) + $pad_length, 
            $pad_string, 
            $pad_type
        );
    }
}

if (!function_exists('mb_strtr')) {
    /**
     * Translate characters or replace substrings
     *
     * @param mixed $str
     * @param string|null $a
     * @param string|null $b
     *
     * @return string
     */
    function mb_strtr(string $str, ?string $from = null, ?string $to = null): string
    {
        $translate = $from;

        if (!is_array($from) && !is_array($to)) {
            $a = (array) $from;
            $b = (array) $to;

            $translate = array_combine(
                array_values($a),
                array_values($b)
            );
        }

        // again weird, but accepts an array in this case
        return strtr($str, $translate);
    }
}

if (!function_exists('mb_str_word_count')) {
    /**
     * Return information about words used in a string
     *
     * @param string $str
     * @param int $format
     * @param string|null $charlist
     *
     * @return mixed
     */
    function mb_str_word_count(string $str, int $format = 2, ?string $charlist = null): mixed
    {
        if ($format < 0 || $format > 2)
            throw new InvalidArgumentException('Argument #2 ($format) must be a valid format value');

        if ($charlist === null) $charlist = '';

        $count = preg_match_all("#[\p{L}\p{N}][\p{L}\p{N}'" . $charlist . ']*#u', $str, $matches, $format === 2 ? PREG_OFFSET_CAPTURE : PREG_PATTERN_ORDER);

        if ($format === 0) return $count;

        $matches = $matches[0] ?? [];

        if ($format === 2) {
            $result = [];

            foreach ($matches as $match) {
                $result[$match[1]] = $match[0];
            }

            return $result;
        }

        return $matches;
    }
}

if (!function_exists('mb_str_shuffle')) {
    /**
     * Randomly shuffles a string
     *
     * @param string $string
     *
     * @return string
     */
    function mb_str_shuffle(string $string): string
    {
        $tmp = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        shuffle($tmp);
        return join('', $tmp);
    }
}

if (!function_exists('mb_count_chars')) {
    /**
     * Returns information about characters used in a string
     *
     * @param string $string   The string to be examined
     * @param int    $mode     Specifies what information to return.
     *                         - 0: Returns an array with the byte-value as key and the frequency of
     *                         every byte as value.
     *                         - 1: Same as 0 but only byte-values with a frequency greater than zero are listed.
     *                         - 2: Same as 0 but only byte-values with a frequency equal to zero are listed.
     *                         - 3: Returns a string containing all unique characters in the string.
     *                         - 4: Returns a string containing all characters in the string that are not used.
     * @param string $encoding [optional] The character encoding. Defaults to 'UTF-8'.
     *
     * @return int[]|string Returns the information requested based on the mode parameter:
     *                      - Mode 0, 1, or 2: returns an array with byte-values as keys and frequencies as values.
     *                      - Mode 3 or 4: returns a string with unique characters or unused characters.
     *
     * @throws ValueError if the mode parameter is not between 0 and 4 (inclusive)
     */
    function mb_count_chars(string $string, int $mode, string $encoding = 'UTF-8'): array|string
    {
        $length = mb_strlen($string, $encoding);
        $char_counts = [];

        for ($i = 0; $i < $length; ++$i) {
            $char = mb_substr($string, $i, 1, $encoding);

            if (!array_key_exists($char, $char_counts)) $char_counts[$char] = 0;

            ++$char_counts[$char];
        }

        return match ($mode) {
            0 => $char_counts,
            1 => array_filter($char_counts, static fn($count): bool => $count > 0),
            2 => array_filter($char_counts, static fn($count): bool => 0 === $count),
            3 => implode('', array_unique(mb_str_split($string, 1, $encoding))),
            4 => implode('', array_filter(array_unique(mb_str_split($string, 1, $encoding)), static fn($char): bool => 0 === $char_counts[$char])),
            default => throw new ValueError('Argument #2 ($mode) must be between 0 and 4 (inclusive)'),
        };
    }
}

if (!function_exists('mb_basename')) {
    /**
     * Returns trailing name component of path
     *
     * @param string $path
     *
     * @return string
     */
    function mb_basename(string $path): string
    {
        if (preg_match('@^.*[\\\\/]([^\\\\/]+)$@s', $path, $matches)) {
            return $matches[1];
        } else if (preg_match('@^([^\\\\/]+)$@s', $path, $matches)) {
            return $matches[1];
        }

        return '';
    }
}

if (!function_exists('mb_strcasecmp')) {
    /**
     * Binary safe case-insensitive string comparison
     *
     * @param string $string1
     * @param string $string2
     * @param string|null $encoding
     *
     * @return int
     */
    function mb_strcasecmp(string $string1, string $string2, ?string $encoding = null): int
    {
        if (null === $encoding) $encoding = mb_internal_encoding();

        return strcmp(
            mb_strtoupper($string1, $encoding), 
            mb_strtoupper($string2, $encoding)
        );
    }
}

if (!function_exists('mb_wordwrap')) {
    /**
     * Wraps a string to a given number of characters
     *
     * @param string $string
     * @param int $width
     * @param string $break
     * @param bool $cut_long_words
     *
     * @return string
     */
    function mb_wordwrap(string $string, int $width = 75, string $break = "\n", bool $cut_long_words = false): string
    {
        $lines = explode($break, $string);

        foreach ($lines as &$line) {
            $line = rtrim($line);

            if (mb_strlen($line) <= $width) continue;

            $words = explode(' ', $line);
            $line = '';
            $actual = '';

            foreach ($words as $word) {
                if (mb_strlen($actual . $word) <= $width) {
                    $actual .= $word . ' ';
                } else {
                    if ($actual != '') {
                        $line .= rtrim($actual) . $break;
                    }

                    $actual = $word;

                    if ($cut_long_words) {
                        while (mb_strlen($actual) > $width) {
                            $line .= mb_substr($actual, 0, $width) . $break;
                            $actual = mb_substr($actual, $width);
                        }
                    }

                    $actual .= ' ';
                }
            }

            $line .= trim($actual);
        }

        return implode($break, $lines);
    }
}

if (!function_exists('mb_preg_match_all')) {
    /**
     * Perform a global regular expression match
     *
     * @param string $pattern
     * @param string $subject
     * @param mixed $matches
     * @param int $flags
     * @param int $offset
     *
     * @return int|false
     */
    function mb_preg_match_all(string $pattern, string $subject, &$matches, int $flags = 0, int $offset = 0): int|false
    {
        $out = preg_match_all(
            $pattern, 
            $subject, 
            $matches, 
            $flags, 
            $offset
        );

        if ($flags & PREG_OFFSET_CAPTURE && is_array($matches) && count($matches) > 0) {
            foreach ($matches[0] as &$match) {
                $match[1] = mb_strlen(substr($subject, 0, (int) $match[1]));
            }
        }

        return $out;
    }
}

if (!function_exists('mb_readline')) {
    /**
     * readline function with unicode support
     *
     * @param string|null $prompt
     *
     * @return string|false
     */
    function mb_readline(?string $prompt = null): string|false
    {
        if ($prompt !== null && $prompt !== '') echo $prompt;

        $line = fgets(STDIN);

        // readline() removes the trailing newline, fgets does not,
        // to emulate the real readline(), we also need to remove it
        if (
            $line !== false && 
            strlen($line) >= strlen(PHP_EOL) && 
            str_ends_with($line, PHP_EOL)
        ) {
            $line = substr($line, 0, -strlen(PHP_EOL));
        }

        return $line;
    }
}

if (!function_exists('mb_htmlentities')) {
    /**
     * Convert all applicable characters to HTML entities
     *
     * @param string $string
     *
     * @return string
     */
    function mb_htmlentities(string $string): string
    {
        $string2 = '';

        // get rid of existing entities else double-escape
        $string = html_entity_decode(stripslashes($string), ENT_QUOTES, 'UTF-8');
        $ar = preg_split('/(?<!^)(?!$)/u', $string);  // return array of every multi-byte character

        foreach ($ar as $c) {
            $o = ord($c);

            if ((strlen($c) > 1) ||  /* multi-byte [unicode] */
                    ($o < 32 || $o > 126) ||  /* <- control / latin weirdos -> */
                    ($o > 33 && $o < 40) ||  /* quotes + ambersand */
                    ($o > 59 && $o < 63) /* html */) {
                // convert to numeric entity
                $c = mb_encode_numericentity($c, array(0x0, 0xFFFF, 0, 0xFFFF), 'UTF-8');
            }

            $string2 .= $c;
        }

        return $string2;
    }
}

if (!function_exists('mb_sprintf')) {
    /**
     * Return a formatted string
     *
     * @param string $format
     *
     * @return string
     */
    function mb_sprintf(string $format): string
    {
        $argv = func_get_args();
        array_shift($argv);
        return mb_vsprintf($format, $argv);
    }
}

if (!function_exists('mb_vsprintf')) {
    /**
     * Works with all encodings in format and arguments.
     * Supported: Sign, padding, alignment, width and precision.
     * Not supported: Argument swapping.
     *
     * @param mixed $format
     * @param mixed $argv
     * @param null|string $encoding
     *
     * @return string
     */
    function mb_vsprintf(string $format, array $argv, ?string $encoding = null): string
    {
        if (is_null($encoding)) $encoding = mb_internal_encoding();

        // Use UTF-8 in the format so we can use the u flag in preg_split
        $format = mb_convert_encoding($format, 'UTF-8', $encoding);

        $newformat = '';  // build a new format in UTF-8
        $newargv = array();  // unhandled args in unchanged encoding

        while ($format !== '') {
            // Split the format in two parts: $pre and $post by the first %-directive
            // We get also the matched groups
            list($pre, $sign, $filler, $align, $size, $precision, $type, $post) =
                preg_split(
                    "!\%(\+?)('.|[0 ]|)(-?)([1-9][0-9]*|)(\.[1-9][0-9]*|)([%a-zA-Z])!u",
                    $format,
                    2,
                    PREG_SPLIT_DELIM_CAPTURE
                );

            $newformat .= mb_convert_encoding($pre, $encoding, 'UTF-8');

            if ($type == '') {
                // didn't match. do nothing. this is the last iteration.
            } elseif ($type == '%') {
                // an escaped %
                $newformat .= '%%';
            } elseif ($type == 's') {
                $arg = array_shift($argv);
                $arg = mb_convert_encoding($arg, 'UTF-8', $encoding);
                $padding_pre = '';
                $padding_post = '';

                // truncate $arg
                if ($precision !== '') {
                    $precision = intval(substr($precision, 1));
                    if ($precision > 0 && mb_strlen($arg, $encoding) > $precision)
                        $arg = mb_substr((string) $precision, 0, $precision, $encoding);
                }

                // define padding
                if ($size > 0) {
                    $arglen = mb_strlen($arg, $encoding);
                    if ($arglen < $size) {
                        if ($filler === '')
                            $filler = ' ';
                        if ($align == '-')
                            $padding_post = str_repeat($filler, (int) $size - $arglen);
                        else
                            $padding_pre = str_repeat($filler, (int) $size - $arglen);
                    }
                }

                // escape % and pass it forward
                $newformat .= $padding_pre . str_replace('%', '%%', $arg) . $padding_post;
            } else {
                // another type, pass forward
                $newformat .= "%$sign$filler$align$size$precision$type";
                $newargv[] = array_shift($argv);
            }
            $format = strval($post);
        }
        // Convert new format back from UTF-8 to the original encoding
        $newformat = mb_convert_encoding($newformat, $encoding, 'UTF-8');
        return vsprintf($newformat, $newargv);
    }
}

if (!function_exists('mb_chunk_split')) {
    /**
     * Split a string into smaller chunks
     *
     * @param string $str
     * @param int $length
     * @param string $separator
     *
     * @return string
     */
    function mb_chunk_split(string $str, int $length = 76, string $separator = "\r\n"): string
    {
        $tmp = array_chunk(
            preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY),
            $length
        );

        $str = '';

        foreach ($tmp as $t) {
            $str .= join('', $t) . $separator;
        }

        return $str;
    }
}

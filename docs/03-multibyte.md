# Multibyte functions

- Replace text within a portion of a string

```php
mb_substr_replace(array|string $string, array|string $replace, array|int $start, mixed $length = null): array|string
```

- Checks if $needle is found in $haystack and returns a boolean value (true/false) whether or not the $needle was found

```php
mb_str_contains(string $haystack, string $needle, $encoding = null): bool
```

- Uppercase the first character of each word in a string

```php
mb_ucwords(string $string, string $encoding = 'UTF-8'): string
```

- Make the first character of a string uppercase

```php
mb_ucfirst(string $string, string $encoding = 'UTF-8'): string
```

- Make a string's first character lowercase

```php
mb_lcfirst(string $string): string
```

- Reverse a string

```php
mb_strrev(string $string, string $encoding = 'UTF-8'): string
```

- Pad a string to a certain length with another string

```php
mb_str_pad(string $input, int $pad_length, string $pad_string = ' ', int $pad_type = \STR_PAD_RIGHT, string $encoding = 'UTF-8'): string
```

- Translate characters or replace substrings

```php
mb_strtr(string $str, ?string $from = null, ?string $to = null): string
```

- Return information about words used in a string

```php
mb_str_word_count(string $str, int $format = 2, ?string $charlist = null): mixed
```

- Randomly shuffles a string

```php
mb_str_shuffle(string $string): string
```

- Returns information about characters used in a string

```php
mb_count_chars(string $string, int $mode, string $encoding = 'UTF-8'): array|string
```

- Returns trailing name component of path

```php
mb_basename(string $path): string
```

- Binary safe case-insensitive string comparison

```php
mb_strcasecmp(string $string1, string $string2, ?string $encoding = null): int
```

- Wraps a string to a given number of characters

```php
mb_wordwrap(string $string, int $width = 75, string $break = "\n", bool $cut_long_words = false): string
```

- Perform a global regular expression match

```php
mb_preg_match_all(string $pattern, string $subject, &$matches, int $flags = 0, int $offset = 0): int|false
```

- readline function with unicode support

```php
mb_readline(?string $prompt = null): string|false
```

- Convert all applicable characters to HTML entities

```php
mb_htmlentities(string $string): string
```

- Return a formatted string

```php
mb_sprintf(string $format): string
```

- Return a formatted string. Supported: Sign, padding, alignment, width and precision. Not supported: Argument swapping

```php
mb_vsprintf(string $format, array $argv, ?string $encoding = null): string
```

- Split a string into smaller chunks

```php
mb_chunk_split(string $str, int $length = 76, string $separator = "\r\n"): string
```
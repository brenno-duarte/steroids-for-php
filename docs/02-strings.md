# String functions

- Convert special characters to HTML entities

```php
htmlspecialchars_recursive(mixed $args): mixed
```

- Add a string before or after

```php
wrap_implode(array $array, string $before = '', string $after = '', string $separator = ''): string
```

- implode an array as key-value pairs

```php
mapped_implode(string $glue, array $array, string $symbol = '='): string
```

- Join pieces with a string recursively

```php
implode_recursive(iterable|string $separator, iterable $pieces = null): string
```

- Find the position of the first occurrence of a substring in a string

```php
strpos_recursive(string $haystack, string $needle, int $offset = 0, array $results = []): string|array
```

- Get a string before the first occurence of the substring. If the substring is not found, the whole string is returned.

```php
str_before(string $string, string $substr): string
```

- Get a string after the first occurence of the substring. If the substring is not found, an empty string is returned.

```php
str_after(string $string, string $substr): string
```

- Replace characters with accents with normal characters.

```php
str_remove_accents(string $string): string
```

- Generate a URL friendly slug from the given string.

```php
str_slug(string $string, string $glue = '-'): string
```

- Truncate String (shorten) with or without ellipsis.

```php
str_shorten(string $string, int $maxLength, bool $addEllipsis = true, bool $wordsafe = false): string
```
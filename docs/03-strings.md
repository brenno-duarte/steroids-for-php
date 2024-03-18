# Strings

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
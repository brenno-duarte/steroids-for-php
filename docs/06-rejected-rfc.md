# Rejected RFC functions

- [ifsetor](https://wiki.php.net/rfc/ifsetor)

```php
ifsetor(mixed $variable, mixed $default = null): mixed
```

- Checks if a int|float value is within a certain bound. [clamp](https://wiki.php.net/rfc/clamp)

```php
clamp(int|float $num, int|float $min, int|float $max): int|float
```

- This function simplifies removal of a value from an array, when the index is not known. [array_delete](https://wiki.php.net/rfc/array_delete)

```php
array_delete(array $array, string $value, bool $strict = true): array
```

- Add an value to array. [array_add](https://wiki.php.net/rfc/array_delete)

```php
array_add(array $array, mixed $value, bool $strict = true): array|false
```
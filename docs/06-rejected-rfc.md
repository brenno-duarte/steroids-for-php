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

- Safe Casting Functions. These functions validate their input to ensure data is not lost with the cast (thus the cast can be considered safe), instead of casting blindly. If the input fails to validate, the to_* functions throw a CastException, while the try_* functions return NULL. If validation succeeds, the converted result is returned.

```php
safe_int(mixed $value)
safe_float(mixed $value)
safe_string(mixed $value)
to_int(mixed $value)
to_float(mixed $value)
to_string(mixed $value)
```
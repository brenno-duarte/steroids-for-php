# Variable handling functions

- Prints human-readable information about a variable in browser

```php
print_r_browser($value): void
```

- Check if an array has some keys

```php
isset_array(...$args): bool
```

- var_export() with short array syntax (square brackets) indented 2 spaces

```php
var_export_short(mixed $expression, bool $return = false): ?string
```

- Dump a human readable value

```php
var_log(mixed $value, string $var_name = '', string $reference = '', string $method = '=', bool $sub = false): mixed
```

- Better GI than print_r or var_dump

```php
html_dump(mixed $var, ?string $var_name = null, ?string $indent = null, ?string $reference = null): void
```

- Returns an array with the name of the defined enums

```php
get_declared_enums(): array
```

- Prints $data followed by a unix newline

```php
println(string $data = ''): int
```
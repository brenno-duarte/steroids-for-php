# Tools

- Get extension from file

```php
file_extension(string $file_name): string
```

- Checks whether a file or directory exists without store result in cache

```php
file_exists_without_cache(string $file_path): bool
```

- Recursively loads all php files in all subdirectories of the given path

```php
autoload_files(string $directory)
```

- Load an object with data in json format

```php
load_object_json(mixed $object, string $json): mixed
```

- Return period in hours, minutes or seconds using microtime function

```php
microtime_period(float $endtime, float $starttime): mixed
```
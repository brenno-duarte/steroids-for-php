# File functions

- Check if a string is present in the contents of a file.

This function is memory usage friendly by not loading the whole contents of the file at once.

```php
file_contains(string $filename, string $string): bool
```    

- Match path against wildcard pattern. This is an extended version of [fnmatch](http://php.net/fnmatch).

* `?` Matches a single character, except `/`
* `#` Matches any decimal characters (0-9)
* `*` Matches any characters, except `/`
* `**` Matches any characters
* `[abc]` Matches `a`, `b` or `c`
* `{ab,cd,ef}` Matches `ab`, `cd` or `ef`

```php
fnmatch_extended(string $pattern, string $path): bool
```

- Get extension from file

```php
file_extension(string $file_name): string
```

- Checks whether a file or directory exists without store result in cache

```php
file_exists_without_cache(string $file_path): bool
```

- Check if directory is empty

```php
is_dir_empty(string $dir): bool
```
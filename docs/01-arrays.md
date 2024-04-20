# Arrays

## Associative arrays

- A general solution for the problem of wanting to know the keys in the callback, and/or retain the key association in the returned array.

```php
array_map_assoc(callable $callback, array $array, array ...$arrays): array
```

- Array slice function that works with associative arrays (keys)

```php
array_slice_assoc(array $array, array $keys): array
```

- Inverse associative version of array_slice_assoc() function

```php
array_slice_assoc_inverse(array $array, array $keys): array
```

- Prepend one or more elements to the beginning of an array

```php
array_unshift_assoc(array $array, mixed $key, mixed $val): array
```

- Remove a portion of the associative array and replace it with something else

```php
array_splice_assoc(array $array, mixed $offset, mixed $length, mixed $replacement): array
```

## Recursive arrays

- Searches the recursive array for a given value and returns the first corresponding array value if successful

```php
array_search_recursive(array $needle, array $haystack): array
```

- Get all values from specific key in a multidimensional array

```php
array_values_recursive(string $key, array $arr): mixed
```

- Shift an element off the beginning of multidimensional array

```php
array_shift_recursive(array $array): array
```

- Get duplicate and unique values from an array

```php
array_unique_recursive(array $array, mixed $key): array
```

- Find all the keys from a multidimensional  array while keeping the array structure

```php
array_keys_recursive($array, $max_depth = INF, $depth = 0, $array_keys = []): array
```

- Recursive in_array function

```php
in_array_recursive(mixed $needle, array $haystack, bool $strict): bool
```

- Applies the callback to the elements of the given recursive arrays

```php
array_map_recursive(callable $callback, array $array): array
```

- Calculate the sum of values in an multidimensional array

```php
array_sum_recursive(array $array): int|float
```

- array_column implementation that works on multidimensional arrays (not just 2-dimensional)

```php
array_column_recursive(array $haystack, mixed $needle): array
```

- Push a multidimensional numeric array into another

```php
array_push_recursive(array $array1, array $array2): array
```

## `array_values()` complements

- Get the first value of array

```php
array_value_first(object|array $array): mixed
```

- Checks if all of given array's elements have a non-falsy value

```php
array_value_empty($array): bool
```

- Count the total number of times a specific value appears in array

```php
array_value_count(mixed $match, array $array): int
```

- Returns the last value from an array

```php
array_value_last(array $array): mixed
```

## `array_column()` complements

- Group values by the same `index_key` in arrays one

```php
array_column_group_key(array $array, int|string $column_key, int|string $index_key): array
```

## `array_search()` complements

- Get all key=>value associations of an array with the given search-value

```php
array_search_with_keys(mixed $needle, mixed $haystack, bool $strict = false): array
```

## `array_push()` complements

- Add elements to an array before a specific index or key

```php
array_push_before(array $src, array $in, int|string $pos): array
```

- Add elements to an array after a specific index or key

```php
array_push_after(array $src, array $in, int|string $pos): array
```

- A function which mimics push() from perl, perl lets you push an array to an array

```php
array_push_array(array &$array): array
```

## `array_combine()` complements

- Preserve duplicate keys when combining arrays

```php
array_combine_identical_keys(array $keys, array $values): array
```

- Creates an array by using one array for keys and another for its values if they are not of same size

```php
array_combine_different_size(array $a, array $b): array
```

## `array_change_key_case()` complements

- Changes the case of all keys in an Unicode array

```php
array_change_key_case_unicode(array $arr, int $c = CASE_LOWER): array
```

- Returns an array with all values lowercased or uppercased.

```php
array_change_value_case(array $array, int $case = CASE_LOWER): array
```

## Others functions for arrays

- Checks if multiple keys exist in an array

```php
array_many_keys_exists(array $array, array|string $keys): bool
```

- array_splice with preserve keys

```php
array_splice_preserve_keys(array $array, int $offset, ?int $length = null): array
```

- Recursively reduces deep arrays to single-dimensional arrays

```php
array_flatten(array $array, int $preserve_keys = 1, array $new_array = []): array
```

- This function simplifies removal of a value from an array, when the index is not known.

```php
array_delete(array $array, string $value, bool $strict = true): array
```

- Delete the given key or index in the array

```php
array_key_delete(array $array, mixed $key, bool $strict = true): array
```

- If you only know a part of a value in an array and want to know the complete value

```php
array_find(string $needle, array $haystack): mixed
```

- Flip an array and group the elements by value

```php
array_group(array $array): array
```

- Add an value to array

```php
array_add(array $array, mixed $value, bool $strict = true): array|false
```

- If two arrays' values are exactly the same (regardless of keys and order)

```php
array_identical_values(array $array1, array $array2): bool
```

- List all the files and folders you want to exclude in a project directory

```php
array_preg_diff(string $needle, string $pattern): string
```

- Convert array to UTF-8

```php
array_encode_utf8(array $array, string $source_encoding): array
```
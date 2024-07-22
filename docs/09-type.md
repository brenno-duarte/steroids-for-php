# Type functions

- Checks whether variable names are set within the global space or they exists as an key and return if they are set (even if their values are null)

```php
is_set(string $var_name, mixed ...$args): bool
```

- Check if variable is an associative array.

```php
is_associative_array(mixed $var): bool
```

- Check if variable is a numeric array.

```php
is_numeric_array(mixed $var): bool
```

- Check if variable can be cast to a string. Returns true for all scalar values except booleans and objects that have a
`__toString` method.

```php
is_stringable(mixed $var): bool
```

- Turn an associated array into a `stdClass` object recursively.

```php
objectify(mixed $var): mixed
```

- Turn an `stdClass` object into an associated array recursively.

```php
arrayify(mixed $var): mixed
```

- Validate that an argument has a specific type.

```php
expect_type(mixed $var, string|string[] $type, string $throwable = null, string $message = null): void
```

As type you can specify any internal type, including `callable` and `object`, a class name or a resource type (eg
`stream resource`). _Typed arrays are **not** supported._

By default a `TypeError` (PHP 7) is thrown. You can specify a class name for any `Throwable` class. For PHP 5 you must
specify the class name.

The message may contain a `%s`, which is replaced by the type of `$var`.

**Example**

```php
expect_type($input, ['array', 'stdClass']);
expect_type($output, ['array', 'stdClass'], 'UnexpectedValueException', "Output should be an array or stdClass object, got a %s");
```
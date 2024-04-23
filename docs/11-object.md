# Object functions
   
- Get the public properties of an object.

Unlike `get_object_vars`, this method will return only public properties regardless of the scope.

The `dynamic` flag controls if the output should be filtered, so only properties defined in the class are set.

```php
object_get_properties(object $object, bool $dynamic = true): array
```

- Set the public properties of an object.

The `dynamic` flag controls if `$data` should be filtered, so only properties defined in the class are set.

```php
object_get_properties(object $object, array $data, bool $dynamic = true): void
```

- Set the public properties of an object

```php
object_to_array(object $object): array
```

- Convert array to the object.

```php
array_to_object(array $array): object
```
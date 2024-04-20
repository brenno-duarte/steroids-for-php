# Reflection functions

- Get function attributes

```php
reflection_get_attributes(object|string $class_name, string|null $method, string $attribute_name): array
```

- Gets a ReflectionProperty for a class's property

```php
reflection_get_property(string|object $class, string $property): mixed
```

- Reports information about an extension

```php
reflection_extension_info(string $extension_name): void
```

- Creates a new class instance from given arguments

```php
reflection_new_instance(object|string $objectOrClass, ...$args): mixed
```

- Creates a new class instance without invoking the constructor

```php
reflection_instance_without_construct(object|string $objectOrClass): mixed
```

- Invokes a reflected method

```php
reflection_invoke_method(object|string $objectOrClass, string $method, ...$args): mixed
```
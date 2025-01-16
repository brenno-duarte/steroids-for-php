# BCMath functions

- Calculates a factorial of given number

```php
bcfact(string $num): string
```

- Checks if a string number is negative starting from its first position

```php
bcnegative(string $number): bool
```

- Check if a string number is decimal or integer

```php
bcisdecimal(string $number): bool
```

- Round a number from lib bcmath (Available since PHP 8.4)

```php
bcround(string $number, int $scale = 0): string
```
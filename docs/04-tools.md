# Tools functions

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

- Takes HEX color code value and converts to a RGB value.

```php
hex2rgb(string $color): string
```

- Takes RGB color value and converts to a HEX color code

```php
rgb2hex(mixed $r, mixed $g = null, mixed $b = null): string
```

- Convert number to word representation.

```php
number_to_word(int $number): string
```

- Convert seconds to real time.

```php
seconds_to_text(int $seconds, bool $returnAsWords = false): string
```

- Convert minutes to real time.

```php
minutes_to_text(int $minutes, bool $returnAsWords = false): string
```

- Convert hours to real time.

```php
hours_to_text(int $hours, bool $returnAsWords = false): string
```

- Returns the number of days for the given month and year.

```php
number_days_in_month(int $month = 0, int $year = 0): int
```

- Converts bytes to human readable size.

```php
bytes2human(int $size, int $precision = 2): string
```
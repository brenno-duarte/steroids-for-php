# Steroids for PHP

Steroids for PHP is a component that enhances PHP's native functions, offering features that are not present in PHP Core.

Some polyfills are included if you don't want to install an extension using PECL.

## Requirements

PHP >= 8.3

## Installing via Composer

```bash
composer require brenno-duarte/steroids-for-php
```

## Documentation

You can access the documentation [here](docs/), or go directly to the `docs/` folder.

## Starting

Steroids for PHP has functions related to:

- [Arrays](docs/01-arrays.md)
- [Strings](docs/02-strings.md)
- [Multibyte functions](docs/03-multibyte.md)
- [Tools](docs/04-tools.md)
- [Reflection functions](docs/05-reflection-functions.md)
- [Some rejected RFC](docs/06-rejected-rfc.md)
- [UTF-8](docs/07-utf8.md)
- [Variable handling functions](docs/08-variable-handling-functions.md)

Some polyfills are included if you don't want to install an extension using PECL.

- [sodium](https://www.php.net/manual/en/book.sodium)
- [yaml](https://www.php.net/manual/en/book.yaml.php)
- [simdjson](https://www.php.net/manual/en/book.simdjson.php)
- [var_representation](https://www.php.net/manual/en/book.var_representation.php)
- [xxtea](https://github.com/xxtea/xxtea-pecl)
<?php

/**
 * VAR REPRESENTATION
 */
if (!defined('VAR_REPRESENTATION_SINGLE_LINE')) {
    define('VAR_REPRESENTATION_SINGLE_LINE', 1);
}

if (!defined('VAR_REPRESENTATION_UNESCAPED')) {
    define('VAR_REPRESENTATION_UNESCAPED', 2);
}

/**
 * VAR_REPRESENTATION
 */
if (!function_exists('var_representation')) {
    /**
     * Convert a variable to a string in a way that fixes the shortcomings of `var_export()`.
     *
     * @param mixed $value
     * @param int $flags bitmask of flags (VAR_REPRESENTATION_SINGLE_LINE, VAR_REPRESENTATION_UNESCAPED)
     * @suppress PhanRedefineFunctionInternal this is a polyfill
     */
    function var_representation($value, int $flags = 0): string
    {
        return PHPPeclPolyfill\VarRepresentation\Encoder::toVarRepresentation($value, $flags);
    }
}

/**
 * XXTEA -----------------------------------------------------------------------------------
 */
if (!extension_loaded('xxtea')) {
    // public functions
    // $str is the string to be encrypted.
    // $key is the encrypt key. It is the same as the decrypt key.
    function xxtea_encrypt(string $str, string $key)
    {
        return PHPPeclPolyfill\XXTEA\XXTEA::encrypt($str, $key);
    }

    // $str is the string to be decrypted.
    // $key is the decrypt key. It is the same as the encrypt key.
    function xxtea_decrypt(string $str, string $key)
    {
        return PHPPeclPolyfill\XXTEA\XXTEA::decrypt($str, $key);
    }
}

/**
 * YAML -----------------------------------------------------------------------------------
 */
if (!extension_loaded('yaml')) {
    function yaml_parse(string $input): mixed
    {
        if (is_file($input)) {
            throw new PHPPeclPolyfill\YAML\YamlException("File found. Use \"yaml_parse_file\"");
        }

        return PHPPeclPolyfill\YAML\YAML::YAMLLoad($input);
    }

    function yaml_parse_file(string $input): mixed
    {
        if (!is_file($input)) {
            throw new PHPPeclPolyfill\YAML\YamlException("String found. Use \"yaml_parse\"");
        }

        return PHPPeclPolyfill\YAML\YAML::YAMLLoad($input);
    }

    function yaml_emit(array $input): mixed
    {
        return PHPPeclPolyfill\YAML\YAML::YAMLDump($input);
    }
}

/**
 * SIMDJSON -----------------------------------------------------------------------------------
 */
if (!function_exists('simdjson_is_valid')) {
    function simdjson_is_valid(string $json, int $depth = 512)
    {
        return PHPPeclPolyfill\Simdjson\Simdjson::simdjsonIsValid($json, $depth);
    }
}

if (!function_exists('simdjson_decode')) {
    function simdjson_decode(string $json, bool $associative = false, int $depth = 512)
    {
        return PHPPeclPolyfill\Simdjson\Simdjson::simdjsonDecode($json, $associative, $depth);
    }
}

if (!function_exists('simdjson_key_count')) {
    function simdjson_key_count(string $json, string $key, int $depth = 512, bool $throw_if_uncountable = false)
    {
        return PHPPeclPolyfill\Simdjson\Simdjson::simdjsonKeyCount($json, $key, $depth, $throw_if_uncountable);
    }
}

if (!function_exists('simdjson_key_exists')) {
    function simdjson_key_exists(string $json, string $key, int $depth = 512)
    {
        return PHPPeclPolyfill\Simdjson\Simdjson::simdjsonKeyExists($json, $key, $depth);
    }
}

if (!function_exists('simdjson_key_value')) {
    function simdjson_key_value(string $json, string $key, bool $associative = false, int $depth = 512)
    {
        return PHPPeclPolyfill\Simdjson\Simdjson::simdjsonKeyValue($json, $key, $associative, $depth);
    }
}
<?php

use PHPUnit\Framework\TestCase;

class SimdjsonTest extends TestCase
{
    private mixed $jsonString;

    public function setUp(): void
    {
        $this->jsonString = <<<'JSON'
        {
        "Image": {
            "Width":  800,
            "Height": 600,
            "Title":  "View from 15th Floor",
            "Thumbnail": {
            "Url":    "http://www.example.com/image/481989943",
            "Height": 125,
            "Width":  100
            },
            "Animated" : false,
            "IDs": [116, 943, 234, 38793, {"p": "30"}]
        }
        }
        JSON;   
    }

    public function testSimdjsonIsValid()
    {
        // Check if a JSON string is valid:
        $isValid = simdjson_is_valid($this->jsonString);  // return bool
        $this->assertTrue($isValid);
    }

    public function testSimdjsonDecode()
    {
        // Parsing a JSON string. similar to the json_decode() function but without the fourth argument
        try {
            // returns array|stdClass|string|float|int|bool|null.
            $parsedJSON = simdjson_decode($this->jsonString, true, 512);
            $this->assertIsArray($parsedJSON); // PHP array
        } catch (RuntimeException $e) {
            echo "Failed to parse ".$this->jsonString.": {$e->getMessage()}\n";
        }
    }

    public function testSimdjsonKeyValue()
    {
        $value = simdjson_key_value($this->jsonString, "Image/Thumbnail/Url");
        $this->assertEquals('http://www.example.com/image/481989943', $value);

        $value = simdjson_key_value($this->jsonString, "Image/IDs/4", true);
        $this->assertEquals(['p' => '30'], $value);
    }

    public function testSimdjsonKeyExists()
    {
        // check if the key exists. return true|false|null. "true" exists, "false" does not exist,
        // throws for invalid JSON.
        $res = simdjson_key_exists($this->jsonString, "Image/IDs/1");
        $this->assertTrue($res);
    }

    public function testSimdjsonKeyCount()
    {
        // count the values
        $res = simdjson_key_count($this->jsonString, "Image/IDs");
        $this->assertEquals(5, $res);
    }
}

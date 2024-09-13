<?php

namespace theodorejb\polycast;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ToStringTest extends TestCase
{
    /**
     * @return list<array{0: string, 1: mixed}>
     */
    public static function shouldPass(): array
    {
        return [
            ["foobar", "foobar"],
            ["123", 123],
            ["123.45", 123.45],
            ["INF", INF],
            ["-INF", -INF],
            ["NAN", NAN],
            ["", ""],
            ["foobar", new Stringable()],
        ];
    }

    /**
     * @param mixed $val
     */
    #[DataProvider("shouldPass")]
    public function testShouldPass(string $expected, $val): void
    {
        $this->assertTrue(safe_string($val));
        $this->assertSame($expected, to_string($val));
    }

    /**
     * @return list<array{0: mixed}>
     */
    public static function disallowedTypes(): array
    {
        return [
            [null],
            [true],
            [false],
            [fopen("data:text/html,foobar", "r")],
            [[]],
        ];
    }

    /**
     * @param mixed $val
     */
    #[DataProvider("disallowedTypes")]
    public function testDisallowedTypes($val): void
    {
        $this->assertFalse(safe_string($val));
        $this->expectException(\Exception::class);
        to_string($val);
    }

    /**
     * @return list<array{0: object}>
     */
    public static function invalidObjects(): array
    {
        return [
            [new \stdClass()],
            [new NotStringable()],
        ];
    }

    /**
     * @dataProvider invalidObjects
     * @param object $val
     */
    #[DataProvider("invalidObjects")]
    public function testInvalidObjects($val): void
    {
        $this->assertFalse(safe_string($val));
        $this->expectException(\Exception::class);
        to_string($val);
    }
}

class NotStringable {}

class Stringable
{
    public function __toString(): string
    {
        return "foobar";
    }
}
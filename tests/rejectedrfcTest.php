<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RejectedRFCTest extends TestCase
{
    public function testClamp()
    {
        $this->assertEquals(1 ,clamp(1, 0, 3));
        $this->assertEquals(2 ,clamp(1, 2, 5));
        $this->assertEquals(3 ,clamp(4, 1, 3));

        $this->expectException("ValueError");
        clamp(0, 2, 1);
    }

    #[DataProvider("provideVariables")]
    public function testVarInfo($variable, $expected)
    {
        $this->assertSame($expected, var_info($variable));
    }

    public static function provideVariables()
    {
        return [
            [[], 'empty array'],
            [[12], 'indexed array'],
            [['key' => 'value'], 'associative array'],
            [[FakeClass::class, 'method'], 'indexed array'],
            [[new FakeClass(), 'method'], 'callable array'],

            [-1.0, 'negative float'],
            [0.0, 'zero float'],
            [-0.0, 'zero float'],
            [1.0, 'positive float'],
            [INF, 'infinite float'],
            [NAN, 'invalid float'],

            [-1, 'negative integer'],
            [0, 'zero integer'],
            [1, 'positive integer'],

            [new \stdClass(), 'object of class stdClass'],
            [new FakeClass(), 'object of class FakeClass'],
            [unserialize('O:1:"A":0:{}'), 'object of class __PHP_Incomplete_Class'],
            [function () {}, 'object of class Closure'],

            [STDIN, 'resource of type stream'],
            [STDOUT, 'resource of type stream'],

            ['', 'empty string'],
            ['string', 'string'],
            ['strlen', 'callable string'],
            ['10', 'numeric string'],
            ['1.0', 'numeric string'],

            [true, 'boolean true'],
            [false, 'boolean false'],
        ];
    }

    public function testIteratorAllAny()
    {
        $this->assertFalse(iterator_any([false]));
        $this->assertTrue(iterator_any([true]));
        $this->assertFalse(iterator_any([0]));
        $this->assertTrue(iterator_any([1]));
        $this->assertFalse(iterator_any([0], fn($x) => $x));
        $this->assertTrue(iterator_any([1], fn($x) => $x));
        
        $this->assertTrue(iterator_all([true, true, true], fn($x) => $x));
        $this->assertTrue(iterator_all([1, 2, 3], fn($x) => $x));
        $this->assertFalse(iterator_all([true, true, false], fn($x) => $x));
        $this->assertFalse(iterator_all([1, 2, 0], fn($x) => $x));
        $this->assertFalse(iterator_all([1, 2, 0]));
    }
}

final class FakeClass
{
    public function method()
    {
    }
}
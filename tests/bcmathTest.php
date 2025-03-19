<?php

use PHPUnit\Framework\TestCase;

class BCMathTest extends TestCase
{
    public function testBcfact()
    {
        $this->assertEquals("265252859812191058636308480000000", bcfact('30'));
    }

    public function testBcnegative()
    {
        $this->assertEquals(true, bcnegative('-3'));
    }

    public function testBcisdecimal()
    {
        $this->assertEquals(true, bcisdecimal('3.3'));
    }

    public function testBcround()
    {
        $this->assertEquals("9.5", bcround('9.487', 1));
    }
}
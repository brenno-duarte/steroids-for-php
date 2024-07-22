<?php

use PHPUnit\Framework\TestCase;

class rejectedrfcTest extends TestCase
{
    public function testClamp()
    {
        $this->assertEquals(1 ,clamp(1, 0, 3));
        $this->assertEquals(2 ,clamp(1, 2, 5));
        $this->assertEquals(3 ,clamp(4, 1, 3));

        $this->expectException("ValueError");
        clamp(0, 2, 1);
    }
}

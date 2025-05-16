<?php

use PHPUnit\Framework\TestCase;

class ToolsTest extends TestCase
{
    public function testLoadObjectJson()
    {
        $class = new class {
            public $first;
            public $last;

            public function fullname()
            {
                return $this->first . ' ' . $this->last;
            }
        };

        $json = '{"first":"John","last":"Smith"}';

        $infull = load_object_json($class, $json);
        $this->assertEquals('John Smith', $infull->fullname());
    }

    public function testHexAndRgb()
    {
        $this->assertEqualsIgnoringCase('rgb(205, 51, 51)', hex2rgb('#CD3333'));
        $this->assertEqualsIgnoringCase('#CD3333', rgb2hex('rgb(205, 51, 51)'));
    }

    public function testNumber()
    {
        $this->assertEquals('one thousand', number_to_word('1000'));
        $this->assertEquals('2 hours, 46 minutes and 40 seconds', seconds_to_text('10000'));
        $this->assertEquals('6 days, 22 hours and 40 minutes', minutes_to_text('10000'));

        $this->assertEquals(
            '1 year, 1 month, 2 weeks, 6 days, 22 hours, 46 minutes and 40 seconds', 
            hours_to_text('10000')
        );

        $this->assertEquals(31, number_days_in_month(10, 2025));
        $this->assertEquals('16 GB', bytes2human('17179869184'));
    }
}
 
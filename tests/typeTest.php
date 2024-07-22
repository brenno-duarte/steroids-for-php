<?php

use PHPUnit\Framework\TestCase;

class typeTest extends TestCase
{
    /* public function testIsSet()
    {
        $arr1 = range(0, 5);
        $arr2 = [
            'a' => 1,
            'b' => 2,
            'c' => 'hELLO wORLD!'
        ];
        $gender = 'male';
        $age = 12;
        $this->assertTrue(is_set('age', 'arr1'));
    } */

    public function testIssetArray()
    {
        $array = [
            'a' => 1,
            'b' => 2,
            'c' => 'hELLO wORLD!'
        ];
        $result = isset_array($array, 'd');
        $this->assertFalse($result);
    }

    public function testIsAssocArray()
    {
        $array1 = [
            'a' => 1,
            'b' => 2,
            'c' => 'hELLO wORLD!'
        ];
        
        $this->assertTrue(is_associative_array($array1));
        $this->assertFalse(is_associative_array([1, 4, "q"]));
    }

    public function testIsNumericArray()
    {
        $array1 = [
            'a' => 1,
            'b' => 2,
            'c' => 'hELLO wORLD!'
        ];
        
        $this->assertFalse(is_numeric_array($array1));
        $this->assertTrue(is_numeric_array([1, 4, 30]));
    }

    public function testIsStringable()
    {
        $this->assertFalse(is_stringable(function () {}));
        $this->assertTrue(is_stringable(100));
        $this->assertTrue(is_stringable("foo"));
    }

    public function testObjectifyAndArrayify()
    {
        $array = ["name" => "brenno", "age" => 25];
        $this->assertIsObject(objectify($array));
        $this->assertIsArray(arrayify(objectify($array)));
    }

    public function testExpectType()
    {
        $this->expectException(TypeError::class);
        expect_type([], "string");
    }
}

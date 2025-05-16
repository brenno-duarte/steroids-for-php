<?php

use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    public function testArrayMapAssoc()
    {
        $array = ['first' => 1, 'second' => 2, 'third' => 3, 'fourth' => 4, 'fifth' => 5];
        $this->assertEquals([
            "first" => 1,
            "second" => 8,
            "third" => 27,
            "fourth" => 64,
            "fifth" => 125
        ], array_map_assoc(function ($key, $val, ...$vals) {
            return ($val * $val * $val);
        }, $array));
    }

    public function testArraySliceAssoc()
    {
        $arr = [
            'name' => 'Nathan',
            'age' => 20,
            'height' => 6
        ];

        $this->assertEquals(["name" => "Nathan", "age" => 20], array_slice_assoc($arr, ['name', 'age']));
        $this->assertEquals(["height" => 6], array_slice_assoc_inverse($arr, ['name', 'age']));
    }

    public function testArraySpliceAssoc()
    {
        $fruit = [
            'orange' => 'orange',
            'lemon' => 'yellow',
            'lime' => 'green',
            'grape' => 'purple',
            'cherry' => 'red',
        ];

        // Replace lemon and lime with apple
        $this->assertEquals([
            "orange" => "orange",
            "apple" => "red",
            "grape" => "purple",
            "cherry" => "red"
        ], array_splice_assoc($fruit, 'lemon', 'grape', ['apple' => 'red']));
    }

    public function testArrayValue()
    {
        $array = ['name' => 'brenno', 'email' => 'brenno@email.com', 'second' => 'brenno', 'last_value'];

        $this->assertTrue(array_many_keys_exists($array, ['name', 'email']));
        $this->assertEquals("brenno", array_value_first($array));
        $this->assertEquals("last_value", array_value_last($array));
        $this->assertEquals(2, array_value_count("brenno", $array));
    }

    public function testArrayValueLast()
    {
        $array = ['name' => 'brenno', 'email' => 'brenno@email.com', 'second' => 'brenno', 'last_value'];
        $this->assertEquals("last_value", array_value_last($array));
    }

    public function testArrayValueEmpty()
    {
        $this->assertFalse(array_value_empty([69, 420, '']));
    }

    public function testArrayColumnGroupKey()
    {
        $records = [
            [
                'id' => 2135,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'company_id' => 1,
            ],
            [
                'id' => 3245,
                'first_name' => 'Sally',
                'last_name' => 'Smith',
                'company_id' => 1,
            ],
            [
                'id' => 5342,
                'first_name' => 'Jane',
                'last_name' => 'Jones',
                'company_id' => 1,
            ],
            [
                'id' => 5623,
                'first_name' => 'Peter',
                'last_name' => 'Doe',
                'company_id' => 2,
            ]
        ];

        $this->assertEquals([
            1 => ["John", "Sally", "Jane"],
            2 => ["Peter"]
        ], array_column_group_key($records, 'first_name', 'company_id'));
    }

    public function testArraySearchWithKeys()
    {
        $array = ['pre' => '2', 1, 2, 3, '1', '2', '3', 'post' => 2];
        $this->assertEquals([
            "pre" => "2",
            1 => 2,
            4 => "2",
            "post" => 2
        ], array_search_with_keys('2', $array));

        $this->assertEquals(["pre" => "2", 4 => "2"], array_search_with_keys('2', $array, true));
        $this->assertEquals([1 => 2, "post" => 2], array_search_with_keys(2, $array, true));
    }

    public function testArrayPush()
    {
        $src = ["A", "B", "C"];
        $in = ["X", "Y"];

        $this->assertEquals(["A", "X", "Y", "B", "C"], array_push_before($src, $in, 1));
        $this->assertEquals(["A", "B", "X", "Y", "C"], array_push_after($src, $in, 1));

        $arr1 = [0];
        $arr2  = [6, 7, 8];

        $this->assertEquals(
            [0, 1, 2, 3, 4, 5, 6, 7, 8],
            array_push_array($arr1, [], [1, 2, 3, 4, 5], $arr2)
        );
    }

    public function testArrayCombine()
    {
        $this->assertEquals([
            "a" => [1, 2],
            "b" => 3
        ], array_combine_identical_keys(array('a', 'a', 'b'), array(1, 2, 3)));

        $this->assertEquals([
            "AL" => "Alabama",
            "AK" => "Alaska",
            "AZ" => "Arizona",
            "AR" => "Arkansas"
        ], array_combine_different_size(
            ["AL", "AK", "AZ", "AR", "TX", "CA"],
            ["Alabama", "Alaska", "Arizona", "Arkansas"]
        ));
    }

    public function testArraySplicePreserveKeys()
    {
        $array = [
            1 => 'a',
            2 => 'b',
            26 => 'z'
        ];

        $this->assertEquals([1 => "a"], array_splice_preserve_keys($array, 0, 1));
        $this->assertEquals([2 => "b", 26 => "z", 1 => "a"], $array);
    }

    public function testArrayChange()
    {
        $arr_unicode = ["FirSt" => 1, "ZażóŁć gęŚlą jaŹń" => ["yağ" => "Oil", "şekER" => "sugar"]];
        $arr_unicode_result_1 = ["FIRST" => 1, "ZAŻÓŁĆ GĘŚLĄ JAŹŃ" => ["YAĞ" => "Oil", "ŞEKER" => "sugar"]];
        $arr_unicode_result_2 = ["first" => 1, "zażółć gęślą jaźń" => ["yağ" => "Oil", "şeker" => "sugar"]];

        $this->assertEquals($arr_unicode_result_1, array_change_key_case_unicode($arr_unicode, CASE_UPPER));
        $this->assertEquals($arr_unicode_result_2, array_change_key_case_unicode($arr_unicode, CASE_LOWER));

        $this->assertEquals(["FIRST", "SECOND"], array_change_value_case(["FirSt", "SecOnd"], CASE_UPPER));
        $this->assertEquals(["first", "second"], array_change_value_case(["FirSt", "SecOnd"], CASE_LOWER));
    }

    public function testArrayFlatten()
    {
        $array = [
            'A' => [
                1 => 'foo',
                2 => [
                    'a' => 'bar'
                ]
            ],
            'B' => 'baz'
        ];

        $this->assertEquals([
            "B" => "baz",
            "A01" => "foo",
            "A020a" => "bar"
        ], array_flatten($array, 0));

        $this->assertEquals([
            "B" => "baz",
            "A11" => "foo",
            "A121a" => "bar"
        ], array_flatten($array, 1));

        $this->assertEquals([
            "B" => "baz",
            "A21" => "foo",
            "A222a" => "bar"
        ], array_flatten($array, 2));
    }

    public function testArrayDelete()
    {
        $this->assertEquals([1 => "email"], array_delete(["name", "email"], "name"));
        $this->assertEquals(
            ["email" => "foo@email.com"],
            array_key_delete(["name" => "foo", "email" => "foo@email.com"], "name")
        );
    }

    /* public function testArrayFindKey()
    {} */

    public function testArrayGroup()
    {
        $array = array_group([1,2,2,3,1,2,0,4,5,2], fn ($a, $b) => $a <= $b);
        
        $this->assertEquals([
            [1, 2, 2, 3],
            [1, 2],
            [0, 4, 5],
            [2]
        ], $array);
    }

    public function testArrayAdd()
    {
        $this->assertEquals(
            ["name" => "brenno", 'age' => 25],
            array_add(["name" => "brenno"], ['age' => 25])
        );
    }

    public function testArrayIdenticalValues()
    {
        $array1 = ["red", "green", "blue"];
        $array2 = ["green", "red", "blue"];
        $array3 = ["red", "green", "blue", "yellow"];
        $array4 = ["red", "yellow", "blue"];
        $array5 = ["x" => "red", "y" =>  "green", "z" => "blue"];

        $this->assertTrue(array_identical_values($array1, $array2));
        $this->assertFalse(array_identical_values($array1, $array3));
        $this->assertFalse(array_identical_values($array1, $array4));
        $this->assertTrue(array_identical_values($array1, $array5));
    }

    public function testArrayPregDiff()
    {
        $this->assertEquals([1 => "files"], array_preg_diff(["./", "files"], '/^\./'));
    }

    public function testArrayTree()
    {
        $array_test = ['name' => 'brenno', 'email' => 'brenno@gmail.com', 'second' => 'brenno', 'last_value'];
        
        $this->assertEquals(
            "name => brenno\nemail => brenno@gmail.com\nsecond => brenno\n0 => last_value\n", 
            array_tree($array_test)
        );
    }

    public function testArrayOnly()
    {
        $array = ['name' => 'brenno', 'email' => 'brenno@email.com', 'second' => 'brenno', 'last_value'];
        $this->assertEquals(
            ['name' => 'brenno', 'email' => 'brenno@email.com'],
            array_only($array, ["name", "email"])
        );
    }

    public function testArrayWithout()
    {
        $array = ['name' => 'brenno', 'email' => 'brenno@email.com', 'second' => 'brenno', 'last_value'];
        $this->assertEquals(
            ['second' => 'brenno', 'last_value'],
            array_without($array, ["name", "email"])
        );
    }

    public function testArrayContains()
    {
        $this->assertTrue(array_contains_all(["foo", "3", 2], [3]));
        $this->assertFalse(array_contains_all(["foo", "3", 2], [3], true));

        $this->assertTrue(array_contains_all_assoc(["foo" => "bar", "number" => "3", 2], ["number" => 3]));
        $this->assertFalse(array_contains_all_assoc(
            ["foo" => "bar", "number" => "3", 2],
            ["number" => 3],
            true
        ));

        $this->assertTrue(array_contains_any(["foo", "3", 2], [3]));
        $this->assertFalse(array_contains_any(["foo", "3", 2], [3], true));

        $this->assertTrue(array_contains_any_assoc(["foo" => "bar", "number" => "3", 2], ["number" => 3]));
        $this->assertFalse(array_contains_any_assoc(
            ["foo" => "bar", "number" => "3", 2],
            ["number" => 3],
            true
        ));
    }

    public function testArrayJoinPretty()
    {
        $this->assertEquals("foo, bar and baz", array_join_pretty(["foo", "bar", "baz"], ", ", " and "));
    }

    public function testArraySearchRecursive()
    {
        $records = [
            [
                'id' => 2135,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'company_id' => 1,
            ],
            [
                'id' => 3245,
                'first_name' => 'Sally',
                'last_name' => 'Smith',
                'company_id' => 1,
            ],
            [
                'id' => 5342,
                'first_name' => 'Jane',
                'last_name' => 'Jones',
                'company_id' => 1,
            ],
            [
                'id' => 5623,
                'first_name' => 'Peter',
                'last_name' => 'Doe',
                'company_id' => 2,
            ]
        ];

        $this->assertEquals([
            'id' => 2135,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'company_id' => 1,
        ], array_search_recursive(['first_name' => 'John'], $records));
    }

    public function testArrayValuesRecursive()
    {
        $multi = [
            [
                "key1" => "value1",
                "key2" => "value2"
            ],
            [
                "key1" => "value3",
                "key2" => "value4"
            ]
        ];

        $this->assertEquals(["value1", "value3"], array_values_recursive("key1", $multi));
    }

    public function testArrayShiftRecursive()
    {
        $array = ['name' => 'brenno', 'email' => 'brenno@gmail.com', 'second' => 'brenno', 'last_value'];
        $expect = ['email' => 'brenno@gmail.com', 'second' => 'brenno', 'last_value'];
        $this->assertEquals($expect, array_shift_recursive($array));
    }

    public function testArrayUniqueRecursive()
    {
        $details = [
            0 => ["id" => "1", "name" => "Mike",    "num" => "9876543210"],
            1 => ["id" => "2", "name" => "Carissa", "num" => "08548596258"],
            2 => ["id" => "1", "name" => "Mathew",  "num" => "784581254"],
        ];

        list($unique, $duplicates) = array_unique_recursive($details, 'id');

        $this->assertEquals([
            ["id" => "1", "name" => "Mike", "num" => "9876543210"],
            ["id" => "2", "name" => "Carissa", "num" => "08548596258"]
        ], $unique);

        $this->assertEquals([0 => ["id" => "1", "name" => "Mathew", "num" => "784581254"]], $duplicates);
    }

    public function testArrayKeysRecursive()
    {
        $array = [
            'Player' => [
                'id' => '4',
                'state' => 'active',
            ],
            'LevelSimulation' => [
                'id' => '1',
                'simulation_id' => '1',
                'level_id' => '1',
                'Level' => [
                    'id' => '1',
                    'city_id' => '8',
                    'City' => [
                        'id' => '8',
                        'class' => 'home',
                    ]
                ]
            ],
            'User' => [
                'id' => '48',
                'gender' => 'M',
                'group' => 'user',
                'username' => 'Hello'
            ]
        ];

        $expect = [
            'Player' => [],
            'LevelSimulation' => [
                'Level' => [
                    'City' => []
                ]
            ],
            'User' => []
        ];

        $this->assertEquals($expect, array_keys_recursive($array));
    }

    public function testInArrayRecursive()
    {
        $details = [
            0 => ["id" => "1", "name" => "Mike",    "num" => "9876543210"],
            1 => ["id" => "2", "name" => "Carissa", "num" => "08548596258"],
            2 => ["id" => "1", "name" => "Mathew",  "num" => "784581254"],
        ];

        $this->assertTrue(in_array_recursive('Mike', $details, true));
    }

    /* public function testArrayMapRecursive()
    {} */

    public function testArraySumRecursive()
    {
        $data = [
            'a' => 5,
            'b' =>
            [
                'c' => 7,
                'd' => 3
            ],
            'e' => 4,
            'f' =>
            [
                'g' => 6,
                'h' =>
                [
                    'i' => 1,
                    'j' => 2
                ]
            ]
        ];

        $this->assertEquals(28, array_sum_recursive($data));
    }

    public function testArrayColumnRecursive()
    {
        $records = [
            [
                'id' => 2135,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'company_id' => 1,
            ],
            [
                'id' => 3245,
                'first_name' => 'Sally',
                'last_name' => 'Smith',
                'company_id' => 1,
            ],
            [
                'id' => 5342,
                'first_name' => 'Jane',
                'last_name' => 'Jones',
                'company_id' => 1,
            ],
            [
                'id' => 5623,
                'first_name' => 'Peter',
                'last_name' => 'Doe',
                'company_id' => 2,
            ]
        ];

        $this->assertEquals(
            ["John", "Sally", "Jane", "Peter"],
            array_column_recursive($records, 'first_name')
        );
    }

    public function testArrayPushRecursive()
    {
        $array1 = [
            0 => [
                "key1" => "value1",
                "key2" => "value2"
            ]
        ];
        
        $array2 = [
            0 => [
                "key1" => "value3",
                "key2" => "value4"
            ]
        ];

        $expect = [
            [
                "key1" => "value1",
                "key2" => "value2"
            ],
            [
                "key1" => "value3",
                "key2" => "value4"
            ]
        ];
        
        $this->assertEquals($expect, array_push_recursive($array1, $array2));
    }

    public function testArrayMapRecursive()
    {
        $inputArray = [
            1, 
            2, 
            [3, 4, [5, 6]], 
            7
        ];

        $result = array_map_recursive(function($value) {
            return $value * 2;
        }, $inputArray);

        $this->assertEquals([2, 4, [6, 8, [10, 12]], 14], $result);
    }

    public function testArrayEncodeUTF8()
    {
        $arr_unicode = ["FirSt" => 1, "ZażóŁć gęŚlą jaŹń" => ["yağ" => "Oil", "şekER" => "sugar"]];
        $this->assertEquals($arr_unicode, array_encode_utf8($arr_unicode));
    }
}

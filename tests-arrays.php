<?php

require_once 'vendor/autoload.php';

$array_test = ['name' => 'brenno', 'email' => 'brenno@gmail.com', 'second' => 'brenno', 'last_value'];
$array_test2 = ['name', 'email'];
$var = ['foo' => 'bar'];
$arr = array(
    'foo' => 'foo',
    'bar' => array(
        'baz' => 'baz',
        'candy' => 'candy',
        'vegetable' => array(
            'carrot' => 'carrot',
        )
    ),
    'vegetable' => array(
        'carrot' => 'carrot2',
    ),
    'fruits' => 'fruits',
);

//print_r(array_find('na', $array_test2));
//var_dump(array_search_with_keys('name', $array_test2));

$src = ["A", "B", "C"];
$in = ["X", "Y"];

//var_dump(array_push_before($src, $in, 1));
//var_dump(array_push_after($src, $in, 1));

$array1 = [
    0 => [
        "key1" => "value1",
        "key2" => "value2"
    ]
];

$array2 = [
    0 => [
        "key1" => "value1",
        "key2" => "value2"
    ]
];

$array_multi = array_push_recursive($array1, $array2);
//var_dump($array_multi);

$arr1 = [0];
$arr2  = [6, 7, 8];
//var_dump(array_push_array($arr1, [], [1, 2, 3, 4, 5], $arr2));

//var_dump(array_values_recursive('key1', $array_multi));

/* $res = array_delete($array_test2, 'name');
var_dump($res); */

/* $res = array_key_delete($array_test, 'name');
var_dump($res); */

/* $res = array_add($arr, ['age' => 25]);
var_dump($res); */

$details = [
    0 => ["id" => "1", "name" => "Mike",    "num" => "9876543210"],
    1 => ["id" => "2", "name" => "Carissa", "num" => "08548596258"],
    2 => ["id" => "1", "name" => "Mathew",  "num" => "784581254"],
];

list($unique, $duplicates) = array_unique_recursive($details, 'id');
//var_dump($unique);
//var_dump($duplicates);

//var_dump(in_array_recursive('Mike', $details, true));
//var_dump(file_extension(__DIR__ . '/fileg.txt'));

//var_dump(array_many_keys_exists($array_test , 'name', 'emails'));

//var_dump(array_value_first($array_test));

/* $array1 = array("red", "green", "blue");
$array2 = array("green", "red", "blue");
$array3 = array("red", "green", "blue", "yellow");
$array4 = array("red", "yellow", "blue");
$array5 = array("x" => "red", "y" =>  "green", "z" => "blue"); */

/* var_dump(array_identical_values($array1, $array2));  // true
var_dump(array_identical_values($array1, $array3));  // false
var_dump(array_identical_values($array1, $array4));  // false
var_dump(array_identical_values($array1, $array5));  // true */

//var_dump(array_preg_diff(scandir(__DIR__), '/^\./'));

//print_r(array_combine_identical_keys(Array('a','a','b'), Array(1,2,3)));
/* print_r(array_combine_different_size(
    ["AL", "AK", "AZ", "AR", "TX", "CA"], 
    ["Alabama", "Alaska", "Arizona", "Arkansas"]
)); */

/* $users_countries = array(
    'username1' => 'US',
    'user2' => 'US',
    'newuser' => 'GB'
);
print_r(array_group($users_countries)); */

$array = array(
    'Player' => array(
        'id' => '4',
        'state' => 'active',
    ),
    'LevelSimulation' => array(
        'id' => '1',
        'simulation_id' => '1',
        'level_id' => '1',
        'Level' => array(
            'id' => '1',
            'city_id' => '8',
            'City' => array(
                'id' => '8',
                'class' => 'home',
            )
        )
    ),
    'User' => array(
        'id' => '48',
        'gender' => 'M',
        'group' => 'user',
        'username' => 'Hello'
    )
);

//print_r(array_keys_recursive($array));

$arr = [
    'name' => 'Nathan',
    'age' => 20,
    'height' => 6
];
//var_dump(array_slice_assoc($arr, ['name','age']));
//var_dump(array_slice_assoc_inverse($arr, ['name','age']));


//$arr = array("FirSt" => 1, "yağ" => "Oil", "şekER" => "sugar");
$arr_unicode = array("FirSt" => 1, "ZażóŁć gęŚlą jaŹń" => ["yağ" => "Oil", "şekER" => "sugar"]);

/* print_r(array_change_key_case($arr_unicode, CASE_UPPER));
print_r(array_change_key_case_unicode($arr_unicode, CASE_UPPER)); */

//var_dump(array_change_value_case($array, CASE_UPPER));

function cube($n)
{
    return ($n * $n * $n);
}

$a = [
    ['first' => 1, 'second' => 2, 'third' => 3, 'fourth' => 4, 'fifth' => 5],
    ['first' => 6, 'second' => 7, 'third' => 8, 'fourth' => 9, 'fifth' => 10]
];

$b = ['first' => 1, 'second' => 2, 'third' => 3, 'fourth' => 4, 'fifth' => 5];

//var_dump(array_map_recursive('cube', $a));
//var_dump(array_map_assoc('cube', $b));

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

//var_dump(array_sum_recursive($data));

$spicy_numbers = [69, 420, ''];
//var_dump(array_value_empty($spicy_numbers));

$array = array(
    'A' => array(
        1 => 'foo',
        2 => array(
            'a' => 'bar'
        )
    ),
    'B' => 'baz'
);

/* var_dump(array_flatten($array, 0));
var_dump(array_flatten($array, 1));
var_dump(array_flatten($array, 2)); */

//var_dump(array_value_count('brenno', $array_test));

//var_dump(array_shift_recursive($array_test));

$records = array(
    array(
        'id' => 2135,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'company_id' => 1,
    ),
    array(
        'id' => 3245,
        'first_name' => 'Sally',
        'last_name' => 'Smith',
        'company_id' => 1,
    ),
    array(
        'id' => 5342,
        'first_name' => 'Jane',
        'last_name' => 'Jones',
        'company_id' => 1,
    ),
    array(
        'id' => 5623,
        'first_name' => 'Peter',
        'last_name' => 'Doe',
        'company_id' => 2,
    )
);

//print_r(array_column_recursive($records, 'first_name'));
//print_r(array_column_group_key($records, 'first_name', 'company_id'));

$fruit = [
    'orange' => 'orange',
    'lemon' => 'yellow',
    'lime' => 'green',
    'grape' => 'purple',
    'cherry' => 'red',
];

// Replace lemon and lime with apple
/* var_dump(array_splice_assoc($fruit, 'lemon', 'grape', ['apple' => 'red']));

// Replace cherry with strawberry
var_dump(array_splice_assoc($fruit, 'cherry', 1, ['strawberry' => 'red'])); */

//var_dump(array_splice_preserve_keys($array_test, 0, 1));
//print_r(array_search_recursive(['first_name' => 'John'], $records));
//print_r(array_encode_utf8($arr_unicode, 'ISO-8859-1'));

print_r(array_tree($array_test));
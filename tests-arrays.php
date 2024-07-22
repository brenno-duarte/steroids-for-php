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


//var_dump(file_extension(__DIR__ . '/fileg.txt'));


//$arr = array("FirSt" => 1, "yağ" => "Oil", "şekER" => "sugar");


/* function cube($key, $val, ...$vals)
{
    return ($val * $val * $val);
}

$a = [
    ['first' => 1, 'second' => 2, 'third' => 3, 'fourth' => 4, 'fifth' => 5],
    ['first' => 6, 'second' => 7, 'third' => 8, 'fourth' => 9, 'fifth' => 10]
];

$b = ['first' => 1, 'second' => 2, 'third' => 3, 'fourth' => 4, 'fifth' => 5];

var_dump(array_map_recursive('cube', $a)); */

$arr_unicode = ["FirSt" => 1, "ZażóŁć gęŚlą jaŹń" => ["yağ" => "Oil", "şekER" => "sugar"]];
print_r(array_encode_utf8($arr_unicode, 'ISO-8859-1'));
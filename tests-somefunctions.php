<?php

require_once 'vendor/autoload.php';

/* var_dump(is_valid_utf8("ZażóŁć gęŚlą jaŹń"));
exit; */

$var = [];

// var_dump(isset($var['foo']) ? "Exists" : "Not exists");
// var_dump(ifsetor($var['foo'], "Not exists"));

/* echo wrap_implode(['line','by','line'], '<b>', '</b>', '<br>  ');
exit;
$implode = [
  'x' => 5,
  'y' => 7,
  'z' => 99,
  'hello' => 'World',
  7 => 'Foo',
]; */

// echo mapped_implode(', ', $implode, ' is ');

/* $array_implode = ['a' => 1, 'b' => 2];
$str =  implode_recursive($array_implode);
var_dump($str);
$str =  implode_recursive(', ', $array_implode);
var_dump($str);
$str =  implode_recursive(['" ', '="'], $array_implode);
var_dump($str);

$iterator = new ArrayIterator($array_implode);
$str =  implode_recursive($iterator);
var_dump($str);
$str =  implode_recursive(', ', $iterator);
var_dump($str);
$str =  implode_recursive(['" ', '="'], $iterator);
var_dump($str); */

$string = 'This is a some string';
$search = 'a';
$found = strpos_recursive($string, $search);

if ($found) {
  foreach ($found as $pos) {
    echo 'Found "' . $search . '" in string "' . $string . '" at position <b>' . $pos . '</b><br />';
  }
} else {
  echo '"' . $search . '" not found in "' . $string . '"';
}

exit;

/* if (is_clean_file(__DIR__ . '/README.md')) {
  echo 'Bad codes this is not image';
} else {
  echo 'This is a real image.';
} */

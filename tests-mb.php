<?php

require_once 'vendor/autoload.php';

//var_dump(is_valid_utf8("ZażóŁć gęŚlą jaŹń"));

// Some Unicode Kanji (漢字はユニコード)
$string = hex2bin('e6bca2e5ad97e381afe383a6e3838be382b3e383bce38389');

// Some Windows-1252 characters (ãƒ)
$contains = hex2bin('e383');
// ^ file_get_contents() produces the same data when it is saved as "ANSI" in Notepad on Windows, 
// so this is not that unrealistic. The only reason to use hex2bin here is to mix character sets 
// without having to use multiple files.
// A character that actually exists in our string. (ー)
$contains2 = hex2bin('e383bc');

/* echo " = Haystack: ".var_export($string, true)."\r\n";
echo " = Needles:\r\n";
echo "   + Windows-1252 characters\r\n";
echo "     - Results:\r\n";
echo "       >    str_contains: ".var_export(str_contains($string, $contains), true)."\r\n";
echo "       > mb_str_contains: ".var_export(mb_str_contains($string, $contains), true)."\r\n";
echo "   + Valid UTF-8 character\r\n";
echo "     - Results:\r\n";
echo "       >    str_contains: ".var_export(str_contains($string, $contains2), true)."\r\n";
echo "       > mb_str_contains: ".var_export(mb_str_contains($string, $contains2), true)."\r\n";
echo "\r\n"; */

//var_dump(mb_ucfirst("àsdf"));
//var_dump(mb_ucwords('åäö'));
//var_dump(mb_strrev('åäö'));
//var_dump(mb_str_pad('æøå', 5));
//var_dump(mb_count_chars('Hello, world!', 3));

/* var_dump(clamp(num: 1, min: 0, max: 3));
var_dump(clamp(num: 1, min: 2, max: 5));
var_dump(clamp(num: 4, min: 1, max: 3));
var_dump(clamp(num: 0, min: 2, max: 1)); */

$var = [];
//var_dump(isset($var['foo']) ? "Exists" : "Not exists");
//var_dump(ifsetor($var['foo'], "Not exists"));

//var_dump(mb_strcasecmp('Daníel', 'DANÍEL'));
//var_dump(mb_wordwrap("A very long woooooooooord.", 8, "|", true));

/* mb_preg_match_all("|<[^>]+>(.*)</[^>]+>|U",
    "<b>example: </b><div align=left>this is a test</div>",
    $out, PREG_PATTERN_ORDER);
echo $out[0][0] . ", " . $out[0][1] . "\n";
echo $out[1][0] . ", " . $out[1][1] . "\n"; */

//echo mb_substr_replace('éggs', 'x', -1);

$arr1 = range(0, 5);
$arr2 = [
  'a' => 1,
  'b' => 2,
  'c' => 'hELLO wORLD!'
];
$gender = 'male';
$age = 12;
//var_dump(is_set('age', 'arr1'));
//var_dump(isset_array($arr2, 'd'));

$array = [
  'str' => 'Test
       spaces',
  0 => 33,
  1 => TRUE,
  [3, 4, 'd', []],
  'arr' => [
    'text with spaces' => '[Tes\'t"s":
 => [
 => 
  [
   {
      spaces',
  ],
  "str2" => "Test's'
 } spaces",
  'arr2' => [
    'text with spaces' => [
      'arr3' => [
        'text with spaces' => 'Te": "st \' => [
      spaces',
      ],
    ],
  ],
];

//var_export_short($array);

//echo var_log($gender);

//echo html_dump($gender);

$yaml = <<<EOD
---
invoice: 34843
date: "2001-01-23"
bill-to: &id001
  given: Chris
  family: Dumars
  address:
    lines: |-
      458 Walkman Dr.
              Suite #292
    city: Royal Oak
    state: MI
    postal: 48046
ship-to: *id001
product:
- sku: BL394D
  quantity: 4
  description: Basketball
  price: 450
- sku: BL4438H
  quantity: 1
  description: Super Hoop
  price: 2392
tax: 251.420000
total: 4443.520000
comments: Late afternoon is best. Backup contact is Nancy Billsmer @ 338-4338.
...
EOD;

$parsed = yaml_parse($yaml);
//var_dump($parsed);

//echo wrap_implode(['line','by','line'], '<b>', '</b>', '<br>  ');

$implode = [
  'x' => 5,
  'y' => 7,
  'z' => 99,
  'hello' => 'World',
  7 => 'Foo',
];

//echo mapped_implode(', ', $implode, ' is ');

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

/* $string = 'This is a some string';
$search = 'a';
$found = strpos_recursive($string, $search);

if ($found) {
  foreach ($found as $pos) {
    echo 'Found "' . $search . '" in string "' . $string . '" at position <b>' . $pos . '</b><br />';
  }
} else {
  echo '"' . $search . '" not found in "' . $string . '"';
} */


class Name
{
  public $first;
  public $last;
  public function fullname()
  {
    return $this->first . " " . $this->last;
  }
}
$json = '{"first":"John","last":"Smith"}';

$infull = load_object_json((new Name), $json);
//echo $infull->fullname();

$num = 5;
$location = 'trée';
$format = 'There are %d monkeys in the %s';
//echo mb_sprintf($format, $num, $location);

print mb_vsprintf("%04d-%02d-%02d", explode('-', '1988-8-1'));
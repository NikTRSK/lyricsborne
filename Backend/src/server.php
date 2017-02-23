<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2/11/2017
 * Time: 5:09 PM
 */

// Generate a list of stop words on load

///* Testing Genius API */
require_once('../vendor/autoload.php');
//require ('simple_html_dom.php');
require ('ProcessData.php');

//$geniusphp = new \Genius\Genius('zVd6jL3FASm1gjIxkeIYLrmrtLE2SGXosQC3_j7voq25Wn3cSSktjp9zvM_nxXD0');

$artistID = 1421;

$dataProessor = new ProcessData();

//$dataProessor->searchArtist('Kendrick Lamar');
//$dataProessor->generateCloud(0);
//
//
$array1 = ["asda", "dasdas", "to", "who", "a", " ", "dasdasdw"];
$arr = array_count_values($array1);
$array2 = ["to", "who", "a", " "];
$array2 = array_flip($array2);

//$test = array_diff_ukey($arr, $array2, 'strcasecmp');
$test = array_diff_key($arr, $array2);

echo $arr["asda"] ."\n";

//print_r ($arr);
//print_r ($array2);
//print_r ($test);

//$array3 = array()/*["dasdasdw" => 5, "obreaf" => 2, "fannfi" => 3]*/;
//
//$ouptut = array();
//foreach ($arr as $key => $value) {
//  if (array_key_exists(strtolower($key), $array3))
//    $array3[$key] += $value;
//  else
//    $array3 = array_merge($array3, array($key=>$value));
//}
//
//echo "After merge\n";
//print_r($arr);
//print_r($array3);

$arr = array();

$arr['a'][] = '2e';
$arr['a'][] = '45';
$arr['a'][] = 'gt';

print_r($arr);
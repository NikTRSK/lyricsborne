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

$dataProessor->searchArtist('Kendrick Lamar');
$dataProessor->generateCloud(0);

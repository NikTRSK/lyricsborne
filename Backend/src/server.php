<?php
session_start(); // This has to be on the top. Initializes a session

require_once('../vendor/autoload.php');
require ('ProcessData.php');

if ( isset($_GET["artistName"]) ) {
  $dataProessor = new ProcessData();
  $_SESSION['dataProessor'] = $dataProessor;
  print_r($_GET["artistName"]. "\n"); // take this out
  $result = $_SESSION['dataProessor']->searchArtist($_GET["artistName"]);
  $_SESSION['dataProessor'] = serialize($dataProessor);
//  print_r(serialize($result));
//echo "\n\n\n";
  echo (serialize($result));

}
else if ( isset($_GET["artistID"]) ) {
  // Return word map for artist
  print_r($_GET["artistID"]. "\n");
  $wordmap = unserialize($_SESSION['dataProessor']);

//  $dp = new ProcessData();
//  $result = $dp->searchArtist('Kendrick Lamar');
  $result = $wordmap->generateCloud(/*$_GET['artistID']*/0);

//  echo "DONE\n";

//  echo "DONE AGAIN\n";
  $_SESSION['dataProessor'] = serialize($wordmap);
//  echo json_encode((array)$wordmap);
}
else if ( isset($_GET["word"]) ) {
  // return songs that match a word
  print_r($_GET["word"]. "\n");
  $songList = $dataProessor->getSongs($_GET["word"]);
  echo json_encode($songList);
}
//else if ( $_GET["songTitle"] && $_GET["artistName"] ) {
//  $lyircs = $dataProessor->getLyrics($_GET["songTitle"], $_GET["artistName"]);
//  echo json_encode($lyircs);
//}
else {
  echo "lyricsBorne API\n";
}
?>
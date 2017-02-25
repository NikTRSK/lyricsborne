<?php
session_start(); // This has to be on the top. Initializes a session

// Generate a list of stop words on load

///* Testing Genius API */
require_once('../vendor/autoload.php');
//require ('simple_html_dom.php');
require ('ProcessData.php');

$dataProessor = new ProcessData();

if ( $_GET["artistName"] ) {
  print_r($_GET["artistName"]. "\n");
  $result = $dataProessor->searchArtist($_GET["artistName"]);
//  print_r($result);
//  echo json_encode($result);
  echo json_encode($result, JSON_FORCE_OBJECT);
}
else if ( $_GET["artistID"] ) {
  // return artist
  print_r($_GET["artistID"]. "\n");
  $wordmap = $dataProessor->generateCloud($_GET["artistID"]);
  echo json_encode($wordmap);
}
else if ( $_GET["word"] ) {
  // return songs that match a word
  print_r($_GET["word"]. "\n");
  $songList = $dataProessor->getSongs($_GET["word"]);
  echo json_encode($songList);
}
else if ( $_GET["songTitle"] && $_GET["artistName"] ) {
  $lyircs = $dataProessor->getLyrics($_GET["songTitle"], $_GET["artistName"]);
  echo json_encode($lyircs);
}
else {
  echo "lyricsBorne API\n";
}
?>
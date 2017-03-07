<?php
session_start(); // This has to be on the top. Initializes a session
error_reporting(E_ERROR | E_PARSE);

require_once('../vendor/autoload.php');
require('ProcessData.php');
require ('MapJSONEncode.php');

if ( isset($_GET["artistName"]) ) {
  // Return a list of valid artists
  $dataProessor = new ProcessData();
  $_SESSION['dataProessor'] = $dataProessor;
  $result = $_SESSION['dataProessor']->searchArtist($_GET["artistName"]);
  $_SESSION['dataProessor'] = serialize($dataProessor);
  echo json_encode($result);
}
else if ( isset($_GET["artistID"]) ) {
  // Return word map for artist
  $processor = unserialize($_SESSION['dataProessor']);
  $result = $processor->generateCloud($_GET['artistID']);
  $_SESSION['dataProessor'] = serialize($processor);
  arsort($result);
//  echo json_encode(new MapJSONEncode(array_slice($result,0,250)), JSON_PRETTY_PRINT);
  echo json_encode(array_slice($result,0,250), JSON_PRETTY_PRINT);
}
else if ( isset($_GET["word"]) ) {
  // Return songs that match a word
  $processor = unserialize($_SESSION['dataProessor']);
  $songList = $processor->getSongs($_GET["word"]);
  $_SESSION['dataProessor'] = serialize($processor);
//  var_dump($songList);
  echo json_encode(new MapJSONEncode($songList), JSON_PRETTY_PRINT);
}
else if ( $_GET["song"] && $_GET["artist"] ) {
  // Return lyric link for a song
  $processor = unserialize($_SESSION['dataProessor']);
  $lyircs = $processor->getLyrics($_GET["song"], $_GET["artist"]);
  $_SESSION['dataProessor'] = serialize($processor);
  echo json_encode($lyircs);
}
else {
  echo "lyricsBorne API\nInvalid query\n";
}
?>
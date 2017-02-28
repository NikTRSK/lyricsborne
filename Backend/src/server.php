<?php
session_start(); // This has to be on the top. Initializes a session

require_once('../vendor/autoload.php');
require ('ProcessData.php');

if ( isset($_GET["artistName"]) ) {
  // Return a list of valid artists
  $dataProessor = new ProcessData();
  $_SESSION['dataProessor'] = $dataProessor;
  $result = $_SESSION['dataProessor']->searchArtist($_GET["artistName"]);
  $_SESSION['dataProessor'] = serialize($dataProessor);
  echo serialize($result);
}
else if ( isset($_GET["artistID"]) ) {
  // Return word map for artist
  $processor = unserialize($_SESSION['dataProessor']);
  $result = $processor->generateCloud($_GET['artistID']);
  $_SESSION['dataProessor'] = serialize($processor);
  echo serialize($result);
}
else if ( isset($_GET["word"]) ) {
  // Return songs that match a word
  $processor = unserialize($_SESSION['dataProessor']);
  $songList = $processor->getSongs($_GET["word"]);
  $_SESSION['dataProessor'] = serialize($processor);
  echo serialize($songList);
}
else if ( $_GET["songTitle"] && $_GET["artistName"] ) {
  // Return lyric link for a song
  $processor = unserialize($_SESSION['dataProessor']);
  $lyircs = $processor->getLyrics($_GET["songTitle"], $_GET["artistName"]);
  $_SESSION['dataProessor'] = serialize($processor);
  echo serialize($lyircs);
}
else {
  echo "lyricsBorne API\nInvalid query\n";
}
?>
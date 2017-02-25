<?php
session_start(); // This has to be on the top. Initializes a session

// Generate a list of stop words on load

///* Testing Genius API */
require_once('../vendor/autoload.php');
//require ('simple_html_dom.php');
require ('ProcessData.php');

//$dp = new ProcessData();
//$result = $dp->searchArtist('Kendrick Lamar');
//$s = $dp->generateCloud(0);

//$dataProessor = new ProcessData();
//$_SESSION['dataProessor'] = $dataProessor;
//print_r($_SESSION);

if ( isset($_GET["artistName"]) ) {
  $dataProessor = new ProcessData();
  $_SESSION['dataProessor'] = $dataProessor;
  print_r($_GET["artistName"]. "\n");
//  $dataProessor = unserialize($_SESSION['dataProessor']);
//  var_dump($_SESSION['dataProessor']);
  $result = $_SESSION['dataProessor']->searchArtist($_GET["artistName"]);
//  $_SESSION['dataProessor'] = $dataProessor;
  $_SESSION['dataProessor'] = serialize($dataProessor);
  print_r(serialize($result));
//  echo json_encode($result);
echo "\n\n\n";
  echo json_encode((array)$result[0]);

}
else if ( isset($_GET["artistID"]) ) {
  // return artist
//  print_r($_GET["artistID"]. "\n");
//  $dataProessor = unserialize($_SESSION['dataProessor']);
//  $wordmap = $_SESSION['dataProessor']->generateCloud($_GET["artistID"]);
//  print_r($_SESSION);
  $wordmap = unserialize($_SESSION['dataProessor']);

//  $dp = new ProcessData();
//  $result = $dp->searchArtist('Kendrick Lamar');
  $result = $wordmap->generateCloud(/*$_GET['artistID']*/0);

  print_r(get_class($wordmap));

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
<?php

/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2/11/2017
 * Time: 5:05 PM
 */

require_once('../vendor/autoload.php');
require ('simple_html_dom.php');
require ('Artist.php');
require ('Song.php');

class ProcessData
{
  private $stopwords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the", ' ');
  private $mWordMap;
  private $mSongsMap;
  private $mArtists;

  private $mSearchCache;
  private $geniusAPI;

  public function __construct() {
    $this->geniusAPI = new \Genius\Genius('zVd6jL3FASm1gjIxkeIYLrmrtLE2SGXosQC3_j7voq25Wn3cSSktjp9zvM_nxXD0');
    $this->mArtists = array();
  }
  /*
    Queries the API for an artist name.
    Returns an array of artists
  */
  public function searchArtist($query) {
    $artistSearch = $this->geniusAPI->search->get($query)->response->hits;
    print_r("SIZE: ". sizeof($artistSearch). "\n");
    // Reset array
    $this->mSearchCache = array();
    $foundIDs = array(); // Stores the ids for the artists in the search cache. Used to filter out duplicates
    foreach($artistSearch as $artist) {
      $artistID = $artist->result->primary_artist->id;
      $artistName = $artist->result->primary_artist->name;
      // If we haven't found the artist create it
      if (stripos($query, $artistName) !== false && in_array($artistID, $foundIDs) === false) {
        $imageURL = $artist->result->primary_artist->image_url;
        $artistItem = new Artist($artistID, $artistName, $imageURL);
        array_push($this->mSearchCache, $artistItem);
        array_push($foundIDs, $artistID);
      }
    }
    return $this->mSearchCache;
  }

  /*
    Queries the API for an artist by ID.
    Generates a word list of word occurences
    and returns a map of words => frequencies
  */
  public function generateCloud($artistID) {
    if (count($this->mSearchCache) < $artistID-1) {
      echo "Value out of range. \n";
      return;
    }
    print_r( "CacheSize: ". sizeof($this->mSearchCache). "\n");
    $artist = $this->mSearchCache[$artistID];
    array_push($this->mArtists, $artist);
//    print_r(sizeof($this->mSearchCache). "\n");
//    print_r($artist->getID());
    $Id = $artist->getID();
    // Get all songs for the artist
    $searchResult = $this->geniusAPI->artists->getSongs($Id, 50, 1)->response;
//    print_r($searchResult);
    // Parse through all the lyrics
    $nextPage = 1;
    $pagesExplored = 0; // Remove. Test this
    $songCount = 0;
//    $lyrics = array();
    while ($nextPage !== null && $songCount <= 20) {
      $pagesExplored++;

      $songs = $searchResult->songs;
      print_r($songCount. ", ". $pagesExplored.  "\n");
      foreach($songs as $s) {
        // Absolute Path to the song
        $songName = $s->title; // song title
        $lyricUrl = $s->url; // url of the lyrics
        print_r($lyricUrl ."\n");
//        $lyricPath = $s->path;
        // Artist ID. Used to drop featured Artists
        $songArtistID = $s->primary_artist->id; // artistID of the song

        // Skip the song if artist is not the primary artist
        print_r($Id. " | ". $songArtistID . "\n");
        if ($Id !== $songArtistID) continue;

//        print_r($lyricUrl); print("\r\n");
        // Get the HTML of the song
        $html = str_get_html(file_get_contents($lyricUrl));
        // If we can't open the HTML, skip
        if ($html === false) {
          echo "Couldn't open $lyricUrl \n";
          continue;
        }
//        $searchTerm = ('a[href/*=' . $lyricPath . ']');
        $lyrics = array();
        $lyricWords = array();
        foreach ($html->find('lyrics') as $l) {
          $tagsRemoved = (preg_replace('/(?s)<.+?>/', ' ', $l->innertext));
          // Remove extras ( <br>, </a>, [..] )
          $finalResult = preg_replace('/(?s)\[.+?\]/', ' ', $tagsRemoved);
          // Split the string into individual words
          $lyricWords = preg_split('/[ ,;:()]+/', $finalResult);

        }
        print_r($lyricWords);
        $wordCount = array_count_values($lyricWords);
        print_r($wordCount);
        // Drop Stop words

        // Add the song into the mSongsMap
        $song = new Song($songName, $artist, $lyricUrl, $wordCount);
        // Merge the wordCount with mWordMap
        $songCount++;
      }
      $nextPage = $searchResult->next_page;
      $searchResult = $this->geniusAPI->artists->getSongs($Id, 50, $nextPage)->response;
    }
  }

  /*
    Returns a list of all the songs that
    contain the word
  */
  public function getSongs($word) {
    $songs = $this->mWordMap[$word];
    // ?? Error handling ??
    return $songs;
  }

  /*
    Returns a lyric link for a song from an artist
  */
  public function getLyrics($songTitle, $artistName) {

  }
}
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
  private $stopwords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the", ' ', '');
  private $mWordMap;
  private $mSongsMap;
  private $mArtists;

  private $mSearchCache;
  private $geniusAPI;

  public function __construct() {
    $this->geniusAPI = new \Genius\Genius('zVd6jL3FASm1gjIxkeIYLrmrtLE2SGXosQC3_j7voq25Wn3cSSktjp9zvM_nxXD0');
    $this->stopwords = array_flip($this->stopwords);
    $this->mArtists = array();
    $this->mWordMap = array();
  }
  /*
    Queries the API for an artist name.
    Returns an array of artists
  */
  public function searchArtist($query) {
    // Query the API for an artist name
    $artistSearch = $this->geniusAPI->search->get($query)->response->hits;
    // Reset the cache array
    $this->mSearchCache = array();
    $foundIDs = array(); // Stores the ids for the artists in the search cache. Used to filter out duplicates
    // Look over all the results and find the artists that match the query
    foreach($artistSearch as $artist) {
      $artistID = $artist->result->primary_artist->id;
      $artistName = $artist->result->primary_artist->name;
      // If we haven't found the artist in the mSearchCache create it
      if (stripos($query, $artistName) !== false && in_array($artistID, $foundIDs) === false) {
        $imageURL = $artist->result->primary_artist->image_url;
        $artistItem = new Artist($artistID, $artistName, $imageURL);
        array_push($this->mSearchCache, $artistItem);
        array_push($foundIDs, $artistID);
      }
    }
    // Return the search results
    return $this->mSearchCache;
  }

  /*
    Queries the API for an artist by ID.
    Generates a word list of word occurences
    and returns a map of words => frequencies
  */
  public function generateCloud($artistID) {
    // If the $artistID is out of range terminate
    if (count($this->mSearchCache) < $artistID-1) {
      return "artistID out of range.\n";
    }
    // The  Genius API doesn't support Serialization so we have to reinitialize it.
    $this->geniusAPI = new \Genius\Genius('zVd6jL3FASm1gjIxkeIYLrmrtLE2SGXosQC3_j7voq25Wn3cSSktjp9zvM_nxXD0');
    // Get the artist we are generating the word cloud for
    $artist = $this->mSearchCache[$artistID];
    // Extract the ID of the artist in the Genius API
    $Id = $artist->getID();
    // Get all songs for the artist. (artistId, songsPerPage, pageNumber)
    $searchResult = $this->geniusAPI->artists->getSongs($Id, 50, 1)->response;
    // Parse through all the lyrics
    $nextPage = 1; // Tracks the next available page
    $songCount = 0;
    // We are limiting the search to only 15 songs to speed up responce time.
    while ($nextPage !== null && $songCount <= 15) {
      // Get all the songs from a page. Genius returns 50 songs max per page
      $songs = $searchResult->songs;
      foreach($songs as $s) {
        // Absolute Path to the song
        $songName = $s->title; // song title
        $lyricUrl = $s->url; // url of the lyrics
        print_r($lyricUrl ."\n");
        // Artist ID. Used to drop featured Artists
        $songArtistID = $s->primary_artist->id; // artistID of the song

        // Skip the song if artist is not the primary artist
        if ($Id !== $songArtistID) continue;

        // Get the HTML of the song
        $html = str_get_html(file_get_contents($lyricUrl));
        // If we can't open the HTML, skip
        if ($html === false) {
//          echo "Couldn't open $lyricUrl \n"; // DEBUG ONLY: Output error message if html empty (lyrics don't exits)
          continue;
        }

        // Extract only the lyrics from the html
        $lyricWords = array();
        foreach ($html->find('lyrics') as $l) {
          $tagsRemoved = (preg_replace('/(?s)<.+?>/', ' ', $l->innertext));
          // Remove extras ( <br>, </a>, [..] )
          $finalResult = preg_replace('/(?s)\[.+?\]/', ' ', $tagsRemoved);
          // Split the string into individual words
          $lyricWords = preg_split('/[ ,;:()]+/', $finalResult);
        }

        // Convert array to lowercase
        array_walk($lyricWords, function(&$value)
        {
          $value = strtolower($value);
        });

        // count the word occurencies
        $wordCount = array_count_values($lyricWords);
        // Drop Stop words
        $filteredOutWords = array_diff_key($wordCount, $this->stopwords);
        // Create the song and assign it to the artist
        $song = new Song($songName, $artist, $lyricUrl, $wordCount);
        $artist->addSong($song);

        // add the song to mSongsMap
        foreach ($filteredOutWords as $key => $value) {
          $this->mSongsMap[$key][] = $song;
        }

        // Merge the wordCount with mWordMap
        foreach ($filteredOutWords as $key => $value) {
          if (isset($this->mWordMap[strtolower($key)]))
            $this->mWordMap[strtolower($key)] += $value;
          else
            $this->mWordMap = array_merge($this->mWordMap, array($key=>strtolower($value)));
        }

        // Increase created song count
        $songCount++;
      }
      // Search the next page
      $nextPage = $searchResult->next_page;
      $searchResult = $this->geniusAPI->artists->getSongs($Id, 50, $nextPage)->response;
    }
    // Push the artist into the mArtists array
    $this->mArtists[$artist->getName()] = $artist;
    // Return the word => frequencies map
    return $this->mWordMap;
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
    foreach ($this->mArtists[$artistName]->getSongs() as $s) {
      if ($s->getTitle() == $songTitle)
        return $s->getLyricsLink();
    }
    return null;
  }
}
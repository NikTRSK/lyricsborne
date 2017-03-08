<?php

require_once('Song.php');

class SongTest extends PHPUnit_Framework_TestCase
{
  public function testGetTitleReturnsTitle() {
    $title = "Test title";
    $song = new Song($title, null, null, null);

    $this->assertEquals($title, $song->getTitle());
  }

  public function testGetTitleNonExistingTitleReturnsNULL() {
    $song = new Song(null, null, null, null);

    $this->assertNull($song->getTitle());
  }

  public function testGetLyricsLinkReturnsLink() {
    $lyricsLink = "http://test.com/test-lyrics";
    $song = new Song(null, null, $lyricsLink, null);

    $this->assertEquals($lyricsLink, $song->getLyricsLink());
  }

  public function testGetLyricsLinkNonExistingLyricsReturnsNULL() {
    $song = new Song(null, null, null, null);

    $this->assertNull($song->getLyricsLink());
  }

  public function testGetOccurenceOfReturnsOccurenceForInputWord() {
    $wordMap = array("test" => 10, "foo" => 8, "bar" => 5);
    $song = new Song(null, null, null, $wordMap);

    $this->assertEquals(8, $song->getOccurenceOf("foo"));
  }

  public function testGetOccurenceOfReturnsOccurenceForNonexistingInputWord() {
    $wordMap = array("test" => 10, "foo" => 8, "bar" => 5);
    $song = new Song(null, null, null, $wordMap);

    $this->assertNull($song->getOccurenceOf("abc"));
  }

  public function testGetArtistReturnsArtist() {
    $id = 1421;
    $artistName = "Test artist";
    $link = "https://genius.com/albums/Kendrick-lamar/Good-kid-m-a-a-d-city";
    $artist = new Artist($id, $artistName, $link);
    $song = new Song(null, $artist, null, null);

    $this->assertNotNull($song->getArtist());
  }

  public function testGetArtistNonExistringArtistReturnsNULL() {
    $song = new Song(null, null, null, null);

    $this->assertNull($song->getArtist());
  }
}

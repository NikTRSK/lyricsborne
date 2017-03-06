<?php

/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 3/5/2017
 * Time: 7:32 PM
 */
require_once('Artist.php');
require_once('Song.php');

class ArtistTest extends PHPUnit_Framework_TestCase
{
  /* getID tests */
  public function testGetIDReturnsValidID() {
    $id = 1421;
    $artistName = "Test artist";
    $link = "https://genius.com/albums/Kendrick-lamar/Good-kid-m-a-a-d-city";
    $artist = new Artist($id, $artistName, $link);

    $this->assertEquals($id, $artist->getID());
  }

  public function testGetIDInvalidIDReturnsNULL() {
    $id = 1421;
    $artistName = null;
    $link = "https://genius.com/albums/Kendrick-lamar/Good-kid-m-a-a-d-city";
    $artist = new Artist($id, $artistName, $link);

    $this->assertEquals(null, $artist->getName());
  }

  /* getName tests */
  public function testGetNameReturnsValidName() {
    $id = 1421;
    $artistName = "Test artist";
    $link = "https://genius.com/albums/Kendrick-lamar/Good-kid-m-a-a-d-city";
    $artist = new Artist($id, $artistName, $link);

    $this->assertEquals($artistName, $artist->getName());
  }

  public function testGetNameInvalidNameReturnsNULL() {
    $id = 1421;
    $artistName = null;
    $link = "https://genius.com/albums/Kendrick-lamar/Good-kid-m-a-a-d-city";
    $artist = new Artist($id, $artistName, $link);

    $this->assertEquals(null, $artist->getName());
  }

  /* getSongs tests */
  public function testGetSongsReturnsSingleSong() {

  }

  public function testGetSongsReturnsMultipleSongs() {

  }

  /* addSong */
  public function testAddSongSingleAddReturnsSingleSong() {
    $song = new Song(null, null, null, null);
    $artist = new Artist(null, null, null);

    $artist->addSong($song);

    $this->assertEquals(1, sizeof($artist->getSongs()));
  }

  public function testAddSongMultipleAddReturnsMultipleSongs() {
    $artist = new Artist(null, null, null);

    for ($i = 0; $i < 5; ++$i) {
      $song = new Song(null, null, null, null);
      $artist->addSong($song);
    }

    $this->assertEquals(5, sizeof($artist->getSongs()));
  }

  public function testAddSongNothingAddedReturnsEmpty() {
    $artist = new Artist(null, null, null);

    $this->assertEmpty($artist->getSongs());
  }

  /* getImageLink */
  public function testGetImageLinkReturnsValidLink() {
    $id = 1421;
    $artistName = "Test artist";
    $link = "https://genius.com/albums/Kendrick-lamar/Good-kid-m-a-a-d-city";
    $artist = new Artist($id, $artistName, $link);

    $this->assertEquals($link, $artist->getImageLink());
  }

  public function testGetImageLinkInvalidReturnsNULL() {
    $id = 1421;
    $artistName = "Test artist";
    $link = null;
    $artist = new Artist($id, $artistName, $link);

    $this->assertEquals(null, $artist->getImageLink());
  }

  /* jsonSerialize */
}

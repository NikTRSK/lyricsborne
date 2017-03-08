<?php

require_once('ProcessData.php');

class ProcessDataTest extends PHPUnit_Framework_TestCase
{
  public function testSearchArtistReturnsSearchResult() {
    $dataProcessor = new ProcessData();
    $searchResult = $dataProcessor->searchArtist("Metallica");

    $this->assertNotEmpty($searchResult);
  }

  public function testSearchArtistNoArtistFoundReturnsEmptyArray() {
    $dataProcessor = new ProcessData();
    $searchResult = $dataProcessor->searchArtist("sgnkd");

    $this->assertEmpty($searchResult);
  }

  public function testGenerateCloudReturnsAMap() {
    $dataProcessor = new ProcessData();
    $dataProcessor->searchArtist("Metallica");
    $wordMap = $dataProcessor->generateCloud(0);

    $this->assertNotEmpty($wordMap);
  }

  public function testGenerateCloudEmptyReturnsAError() {
    $dataProcessor = new ProcessData();
    $dataProcessor->searchArtist("sgnkd");
    $wordMap = $dataProcessor->generateCloud(0);

    $this->assertNotEmpty($wordMap);
  }

  public function testGetSongsReturnsSongs() {
    $dataProcessor = new ProcessData();
    $dataProcessor->searchArtist("Metallica");
    $dataProcessor->generateCloud(0);
    $result = $dataProcessor->getSongs("kill");

    $this->assertNotEmpty($result);
  }

  public function testGetSongsReturnsEmptyOnNonexistentWordQuery() {
    $dataProcessor = new ProcessData();
    $dataProcessor->searchArtist("Metallica");
    $dataProcessor->generateCloud(0);
    $result = $dataProcessor->getSongs("njodfanojda");

    $this->assertEmpty($result);
  }

  public function testGetLyricsReturnsLyricsIfOneArtistInCloud() {
    $dataProcessor = new ProcessData();
    $dataProcessor->searchArtist("Metallica");
    $dataProcessor->generateCloud(0);
    $result = $dataProcessor->getLyrics("Enter+Sandman", "Metallica");

    $this->assertNotEmpty($result);
  }

  public function testGetLyricsReturnsLyricsIfMultipleArtistInCloud() {
    $dataProcessor = new ProcessData();
    $dataProcessor->searchArtist("Metallica");
    $dataProcessor->generateCloud(0);
    $result = $dataProcessor->getLyrics("njodfanojda", "Metallica");

    $this->assertEmpty($result);
  }
}

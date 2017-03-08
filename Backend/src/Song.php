<?php

/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2/11/2017
 * Time: 5:05 PM
 */
class Song implements JsonSerializable
{
  private $mTitle;
  private $mArtist;
  private $mLyricLink;
  private $mWordMap;

  public function __construct($title, $artist, $lyricsLink, $wordMap) {
    $this->mTitle = $title;
    $this->mArtist = $artist;
    $this->mLyricLink = $lyricsLink;
    $this->mWordMap = $wordMap;
  }

  public function getTitle() {
    return $this->mTitle;
  }

  public function getLyricsLink() {
    return $this->mLyricLink;
  }

  public function getOccurenceOf($word) {
    if (isset($this->mWordMap[$word]))
      return $this->mWordMap[$word];
    return null;
  }

  public function getArtist() {
    if ($this->mArtist)
      return $this->mArtist->getName();
    return null;
  }

  /**
   * @codeCoverageIgnore
   */
  // function called when encoded with json_encode
  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}
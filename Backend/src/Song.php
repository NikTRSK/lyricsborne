<?php

/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2/11/2017
 * Time: 5:05 PM
 */
class Song
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
}
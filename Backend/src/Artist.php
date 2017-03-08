<?php

/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2/11/2017
 * Time: 5:05 PM
 */
class Artist implements JsonSerializable
{
    private $mID;
    private $mName;
    private $mSongs;
    private $mImageURL;

    public function __construct($id, $name, $imageURL) {
      $this->mID = $id;
      $this->mName = $name;
      $this->mImageURL = $imageURL;

      $this->mSongs = array();
    }

    public function getID() {
        return $this->mID;
    }

    public function getName() {
        return $this->mName;
    }

    public function addSong($song) {
        array_push($this->mSongs, $song);
    }

    public function getSongs( ) {
        return $this->mSongs;
    }

    public function getImageLink() {
        return $this->mImageURL;
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
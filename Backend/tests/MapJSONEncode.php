<?php

/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2/27/2017
 * Time: 6:22 PM
 */
class MapJSONEncode implements JsonSerializable
{
  public function __construct(array $array)
  {
    $this->array = $array;
  }

  public function jsonSerialize()
  {
//    return $this->array;
    return $this->array;
  }
}
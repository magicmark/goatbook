<?php

include_once "FaceDetector.php";

class Image {
  
  // could add getters and setters but screw that.
  public $name;
  public $size;
  public $type;
  public $face;
  public $img;
  
  function __construct($name) {
    $this->name = $name;
    $this->size = getimagesize($this->name);
    $extension = pathinfo($content, PATHINFO_EXTENSION);
    if ($extension = "png") {
      $this->type = "png";
    } else if ($extension = "jpg") {
      $this->type = "jpeg";
    } else if ($extension = "jpeg") {
      $this->type = "jpeg";
    } else {
      $this->type = "png";
    }
    //$this->type = str_replace("image/","",image_type_to_mime_type($this->size[2]));

    // should maybe place this in seperate function?
    $func = 'imagecreatefrom' . $this->type;
    $this->img = $func($this->name);


  }

  function getFace(){
    $this->detector = new svay\FaceDetector('detection.dat');
    $this->detector->faceDetect($this->name);
    $this->face = $this->detector->getFace();
  }
}
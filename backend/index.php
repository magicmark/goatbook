<?php
ini_set("memory_limit","128M");
set_time_limit(0);
include "image.php";

$source   = new Image('images/mark.jpg');
$overlay  = new Image('images/goathead.png');

$source->getFace();
if (!isset($source->face['x'])){
  $source->face['x'] = $source->size[0] / 3;
  $source->face['y'] = $source->size[1] / 3;
}

$canvas = imagecreatetruecolor($source->size[0], $source->size[1]);

$faceOffset = array(
  'x' => -50,
  'y' => -100
);
$facePos = array(
  'x' => $source->face['x'] + $faceOffset['x'],
  'y' => $source->face['y'] + $faceOffset['y'],
);

header('Content-Type: image/jpeg');

imagecopy($canvas, $source->img, 0, 0, 0, 0, $source->size[0], $source->size[1]);
imagecopy($canvas, $overlay->img, $facePos['x'], $facePos['y'], 0, 0, $overlay->size[0], $overlay->size[1]);
imagejpeg($canvas);
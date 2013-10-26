<?php

ini_set("memory_limit","128M");
set_time_limit(0);
include "../backend/image.php";
include "../db.php";

function goatify ($img) {

  $source   = new Image('../backend/humanfaces/'.$img);
  $overlay  = new Image('../backend/images/goathead.png');

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

  //header('Content-Type: image/jpeg');

  imagecopy($canvas, $source->img, 0, 0, 0, 0, $source->size[0], $source->size[1]);
  imagecopy($canvas, $overlay->img, $facePos['x'], $facePos['y'], 0, 0, $overlay->size[0], $overlay->size[1]);
  imagejpeg($canvas, 'goatfaces/' . $img);

  return true;

}


require '../backend/class-Clockwork.php';
$API_KEY = '873be9ec15eb56abca6c87d24da9199931090173';

$from = $_POST['from'];
$to = $_POST['to'];
$content = $_POST['content'];
$msg_id - $_POST['msg_id'];

if (strpos($content,"http://") !== false) {
  $extension = pathinfo($content, PATHINFO_EXTENSION);
  if (!in_array($extension, array("png","jpg"))) {
    die('uh oh!');
  }
  // filename not really all that random, but for this will more than suffice!
  $filename = md5("random" . time() . rand() . "goats") . '.' . $extension;
  file_put_contents('../backend/humanfaces/' . $filename, fopen($content, 'r'));
}


if (!goatify($filename)) {
  die('uh oh!');
}

try
{

  $stmt = $db->prepare(
    "INSERT INTO goats (id, file, source, uploaded) " .
    "VALUES (NULL, :filename, :source, :uploaded)"
  );
  $stmt->bindValue(':filename', $filename,  PDO::PARAM_STR);
  $stmt->bindValue(':source', $content,  PDO::PARAM_STR);
  $stmt->bindValue(':uploaded', $time(),  PDO::PARAM_INT);
  $stmt->execute();
  $id =  $db->lastInsertId();

} catch(PDOException $ex) {
  die("uh oh! Error!" . $ex->getMessage());
}

try
{
  // Create a Clockwork object using your API key
  $clockwork = new Clockwork( $API_KEY );

  // Setup and send a message
  $message = array( 'to' => $from, 'message' => 'Thank you for using GoatBook! http://goat.vladh.net/view.php?id='.$id);

  // Check if the send was successful
  if($result['success']) {
    echo 'Message sent - ID: ' . $result['id'];
  } else {
    echo 'Message failed - Error: ' . $result['error_message'];
  }
}

catch (ClockworkException $e)
{
  echo 'Exception sending SMS: ' . $e->getMessage();
}

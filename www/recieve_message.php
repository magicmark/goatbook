<?php

ini_set("memory_limit","128M");
set_time_limit(0);
include "../backend/image.php";
include "../db.php";


function goatify ($img) {

  $source   = new Image('../backend/humanfaces/'.$img);
  $goat = 'g'.rand(1,3).'.png';
  $overlay  = new Image('../backend/images/'.$goat);
  $scale = 1.4;

  $source->getFace();

  if (!isset($source->face['x'])){
    $source->face['x'] = $source->size[0] / 3;
    $source->face['y'] = $source->size[1] / 3;
  }

  $goatSize = array(
    'x' => intval($source->face['w'] * $scale),
    'y' => intval($source->face['w'] * $scale)
  );

  $canvas = imagecreatetruecolor($source->size[0], $source->size[1]);
  $goathead = imagecreatetruecolor($goatSize['x'], $goatSize['y']);

  $offset = (($source->face['w'] * 1.2) - $source->face['w']) / 2;
  $facePos = array(
    'x' => $source->face['x'] - $offset,
    'y' => $source->face['y'] - $offset,
  );


  //header('Content-Type: image/jpeg');

  imagecolortransparent($goathead, imagecolorallocate($goathead, 0, 0, 0));
  imagealphablending($goathead, false);
  imagesavealpha($goathead, true);
  imagecopyresampled($goathead, $overlay->img, 0, 0, 0, 0, $goatSize['x'], $goatSize['y'], $overlay->size[0], $overlay->size[1]);

  imagecopy($canvas, $source->img, 0, 0, 0, 0, $source->size[0], $source->size[1]);
  imagecopy($canvas, $goathead, $facePos['x'], $facePos['y'], 0, 0, $goatSize['x'], $goatSize['y']);
  imagejpeg($canvas, 'goatfaces/' . $img);

  return true;

}


require '../backend/class-Clockwork.php';
$API_KEY = '873be9ec15eb56abca6c87d24da9199931090173';

$from = $_POST['from'];
$to = $_POST['to'];
$content = $_POST['content'];
//$msg_id - $_POST['msg_id'];

if ((strpos($content,"http://") !== false) || (strpos($content,"https://") !== false) ){
  $extension = pathinfo($content, PATHINFO_EXTENSION);
  if (!in_array($extension, array("png","jpg","jpeg"))) {
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

  $stmt = $pdo->prepare(
    "INSERT INTO goats (id, file, source, uploaded) " .
    "VALUES (NULL, :filename, :source, :uploaded)"
  );
  $stmt->bindValue(':filename', $filename,  PDO::PARAM_STR);
  $stmt->bindValue(':source', $content,  PDO::PARAM_STR);
  $stmt->bindValue(':uploaded', time(),  PDO::PARAM_INT);
  $stmt->execute();
  $id =  $pdo->lastInsertId();

} catch(PDOException $ex) {
  die("uh oh! Error!" . $ex->getMessage());
}

try
{
  // Create a Clockwork object using your API key
  $clockwork = new Clockwork( $API_KEY );

  // Setup and send a message
  $message = array( 'to' => $from, 'message' => "Thank you for choosing GoatBook! We know you have a wide choice when it comes to Goat Apps, and we're grateful you chose ours.\n\nhttp://goat.vladh.net/view.php?id=".$id);
  $result = $clockwork->send( $message );

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

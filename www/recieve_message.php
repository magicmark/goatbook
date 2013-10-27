<?php

ini_set("memory_limit","128M");
set_time_limit(0);
include "../backend/image.php";
include "../db.php";


function goatify ($img) {

  $source   = new Image('../backend/humanfaces/'.$img);
  $goat = 'g'.rand(1,2).'.png';
  $overlay  = new Image('../backend/images/'.$goat);
  $scale = 1.4;
  $moreFaces = true;

  do {
  
  $source->getFace();

  if (!isset($source->face['x'])){
    $moreFaces = false;
  }
  if($moreFaces) {
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
  }
  }
  while($moreFaces);
  return true;

}


require '../backend/class-Clockwork.php';
$API_KEY = '873be9ec15eb56abca6c87d24da9199931090173';

$from = $_POST['from'];
$to = $_POST['to'];
$content = $_POST['content'];

//$msg_id - $_POST['msg_id'];
$imageExists = true;

if ((strpos($content,"http://") !== false) || (strpos($content,"https://") !== false) ){
  $extension = pathinfo($content, PATHINFO_EXTENSION);
  if (!in_array($extension, array("png","jpg","jpeg"))) {
    $imageExists = false; 
  }
  if(!$checkFile = curl_init($content)) {
	$imageExists = false;
  } else { 
    // filename not really all that random, but for this will more than suffice!
    $filename = md5("random" . time() . rand() . "goats") . '.' . $extension;
    file_put_contents('../backend/humanfaces/' . $filename, fopen($content, 'r'));
  }
} else {
  // get url of profile picture in $json["data"]["url"]
  // if profile does not exist, imageExists is set to false
  $content = 'https://graph.facebook.com/'.$content.'/picture?width=500&height=500&redirect=false';
  $file = file_get_contents($content, false,
    stream_context_create(
      array(
        'http' => array(
          'ignore_errors' => true
         )
      )
    ));
  $json = json_decode($file, true); 
  if(array_key_exists("error", $json)) {
    $imageExists = false;
  } else {
    $filename = md5("random" . time() . rand() . "goats") . '.jpg';
	$content = $json["data"]["url"];
    file_put_contents('../backend/humanfaces/' . $filename, fopen($content, 'r'));
  } 
}

// only look for faces if the image actually exists
if($imageExists) {
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
}

try
{
  // Create a Clockwork object using your API key
  $clockwork = new Clockwork( $API_KEY );
// set $faces to true for now until we actually do something with it
  $faces = true;
	
  // Setup and send a message
  if(!$imageExists || !$faces) {
	$message = "Thank you for choosing GoatBook! Unfortunately, the image you wanted to Goatify does not exist or I have found no faces to goat. Please send me another image.";
  } else {
    $message = "Thank you for choosing GoatBook! We know you have a wide choice when it comes to Goat Apps, and we're grateful you chose ours.\n\nhttp://goat.vladh.net/view.php?id=".$id;
  }
  $message = array( 'to' => $from, 'message' => $message);
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

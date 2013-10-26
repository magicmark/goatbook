<?php
require_once '../backend/base.php';

$from = $_POST['from'];
$to = $_POST['to'];
$content = $_POST['content'];
$msg_id - $_POST['msg_id'];


// It works, we don't need to send a message back.

try
{
    // Create a Clockwork object using your API key
    $clockwork = new Clockwork( API_KEY );
 
    // Setup and send a message
    $message = array( 'to' => $from, 'message' => 'Here\'s your URL back: '. $content );
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

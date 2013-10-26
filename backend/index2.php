 <?php
require_once 'base.php';

try
{
    // Create a Clockwork object using your API key
    $clockwork = new Clockwork( API_KEY );
 
    // Setup and send a message
    $message = array( 'to' => '447716743842',  'message' => 'This is a test!' );
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

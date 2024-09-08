<?php
require 'vendor/autoload.php'// Include Composer's autoloader

use Twilio\Rest\Client;

// Your Twilio credentials
$sid = 'your account SID'; // Replace with your Account SID
$token = 'your auth token '; // Replace with your Auth Token

// Create a new Twilio client
$client = new Client($sid, $token);

try {
    // Fetch messages
    $messages = $client->messages->read([
        'limit' => 10 // Adjust the limit as needed
    ]);

    // Display messages
    foreach ($messages as $message) {
        echo "From: " . htmlspecialchars($message->from) . "<br>";
        echo "To: " . htmlspecialchars($message->to) . "<br>";
        echo "Body: " . htmlspecialchars($message->body) . "<br>";
        echo "Date Sent: " . htmlspecialchars($message->dateSent->format('Y-m-d H:i:s')) . "<br><br>";
    }
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
?>

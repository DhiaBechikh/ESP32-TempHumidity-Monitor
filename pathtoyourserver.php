<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

use Twilio\Rest\Client;

// Your Twilio credentials
$sid = 'SID'; // Replace with your Account SID
$token = 'Token'; // Replace with your Auth Token
$twilio_number = 'twilio number';  // Replace with your Twilio phone number

// Recipient's phone number
$to_number = 'recipient phone number'; // Replace with the recipient's phone number

// Create a new Twilio client
$client = new Client($sid, $token);

include 'config.php'; // Ensure this file contains your database connection setup

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO test (t, h) VALUES (?, ?)");
    $stmt->bind_param("dd", $temperature, $humidity);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();

    // Define thresholds
    $tempThreshold = 100; // Example temperature threshold
    $humidityThreshold = 100; // Example humidity threshold

    // Check if the temperature or humidity exceeds the threshold
    if ($temperature > $tempThreshold || $humidity > $humidityThreshold) {
        // Send SMS
        try {
            $message = $client->messages->create(
                $to_number, // To
                [
                    'from' => $twilio_number, // From
                    'body' => 'Alert: Temperature or humidity has exceeded the threshold. Temperature: ' . $temperature . ', Humidity: ' . $humidity
                ]
            );
            echo "Alert SMS sent successfully!";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>

<?php
include 'config.php';

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
} else {
    echo "Invalid request method.";
}

$conn->close();
?>

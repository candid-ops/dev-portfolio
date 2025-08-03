<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";     // or 127.0.0.1
$user = "root";          // default XAMPP/MySQL username
$password = "";          // default is empty in XAMPP
$dbname = "portfolio";

// Connect to database
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// If POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    // Simple validation
    if (empty($name) || empty($email) || empty($message)) {
        echo "All fields are required.";
        exit;
    }

    // Prepare SQL query
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute
    if ($stmt->execute()) {
        echo "Message saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

?>
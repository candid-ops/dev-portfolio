<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug
echo "DEBUG VERSION 2025-08-11<br>";

// DB config
$host = "localhost";
$user = "root";
$password = "";
$dbname = "portfolio";

// Connect
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
    header("Location: index.php#contact");
    exit;
}

// Handle POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email = htmlspecialchars(trim($_POST["email"] ?? ''));
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));

    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: index.php#contact");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    if (!$stmt) {
        $_SESSION['error'] = "Prepare failed: " . $conn->error;
        header("Location: index.php#contact");
        exit;
    }

    $stmt->bind_param("sss", $name, $email, $message);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Message sent successfully!";
    } else {
        $_SESSION['error'] = "Database insert error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to form
    header("Location: index.php#contact");
    exit;
}

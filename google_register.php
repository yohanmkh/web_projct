<?php
// google_register.php
session_start();
require 'config.php';

// Get raw POST data
$data = json_decode(file_get_contents("php://input"), true);
$email = $conn->real_escape_string($data['email']);
$name = $conn->real_escape_string($data['name']);
$uid = $conn->real_escape_string($data['uid']);

// Check if user already exists
$query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Register the new user
    $query = "INSERT INTO users (username, email, google_uid) VALUES ('$name', '$email', '$uid')";
    $conn->query($query);
    $user_id = $conn->insert_id;
    $user = ['id' => $user_id, 'username' => $name];
}

// 🔥 SET SESSION after login with Google
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];

echo "Login successful!";
?>

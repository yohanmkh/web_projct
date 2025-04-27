<?php
// config.php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'so_app';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>

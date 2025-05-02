<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$answer_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

// First get the question_id for redirect
$query = "SELECT question_id FROM answers WHERE id = $answer_id AND user_id = $user_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("You don't have permission to delete this answer.");
}

$answer = $result->fetch_assoc();
$question_id = $answer['question_id'];

// Delete the answer
if ($conn->query("DELETE FROM answers WHERE id = $answer_id AND user_id = $user_id")) {
    header("Location: view_question.php?id=" . $question_id);
    exit;
} else {
    die("Error deleting answer: " . $conn->error);
}
?> 
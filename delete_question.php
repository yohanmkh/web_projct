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

$question_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

// First check if the question belongs to the user
$query = "SELECT * FROM questions WHERE id = $question_id AND user_id = $user_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("You don't have permission to delete this question.");
}

// Delete all votes for answers associated with the question
$conn->query("DELETE FROM answer_votes WHERE answer_id IN (SELECT id FROM answers WHERE question_id = $question_id)");

// Delete all answers associated with the question first (due to foreign key constraint)
$conn->query("DELETE FROM answers WHERE question_id = $question_id");

// Then delete the question
if ($conn->query("DELETE FROM questions WHERE id = $question_id")) {
    header("Location: my_questions.php");
    exit;
} else {
    die("Error deleting question: " . $conn->error);
}
?> 
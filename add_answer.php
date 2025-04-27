<?php
// add_answer.php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $conn->real_escape_string($_POST['content']);
    $question_id = intval($_POST['question_id']);
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO answers (question_id, content, user_id, created_at, rating) VALUES ($question_id, '$content', $user_id, NOW(), 0)";
    if ($conn->query($query)) {
        header("Location: view_question.php?id=" . $question_id);
        exit;
    } else {
        die("Error: " . $conn->error);
    }
}
?>

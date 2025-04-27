<?php
// rate_answer.php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    echo "Please login.";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answer_id = intval($_POST['answer_id']);
    $rating_change = intval($_POST['rating']);
    // Update the answer rating
    $query = "UPDATE answers SET rating = rating + $rating_change WHERE id = $answer_id";
    if ($conn->query($query)) {
        $result = $conn->query("SELECT rating FROM answers WHERE id = $answer_id");
        $answer = $result->fetch_assoc();
        echo $answer['rating'];
    } else {
        echo "Error";
    }
}
?>

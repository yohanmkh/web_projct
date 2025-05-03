<?php
require 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'Please log in to vote']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['error' => 'Invalid request method']));
}

$answer_id = isset($_POST['answer_id']) ? (int)$_POST['answer_id'] : 0;
$vote_type = isset($_POST['vote_type']) ? $_POST['vote_type'] : '';
$user_id = $_SESSION['user_id'];

if (!$answer_id || !in_array($vote_type, ['upvote', 'downvote'])) {
    die(json_encode(['error' => 'Invalid parameters']));
}

try {
    // Check if user has already voted on this answer
    $check_query = "SELECT id, vote_type FROM answer_votes 
                    WHERE answer_id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $answer_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_vote = $result->fetch_assoc();

    if ($existing_vote) {
        if ($existing_vote['vote_type'] === $vote_type) {
            // Remove the vote if clicking the same button
            $delete_query = "DELETE FROM answer_votes WHERE id = ?";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param("i", $existing_vote['id']);
            $stmt->execute();
        } else {
            // Update the vote if changing from upvote to downvote or vice versa
            $update_query = "UPDATE answer_votes SET vote_type = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $vote_type, $existing_vote['id']);
            $stmt->execute();
        }
    } else {
        // Insert new vote
        $insert_query = "INSERT INTO answer_votes (answer_id, user_id, vote_type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iis", $answer_id, $user_id, $vote_type);
        $stmt->execute();
    }

    // Get updated vote counts
    $count_query = "SELECT 
        SUM(CASE WHEN vote_type = 'upvote' THEN 1 ELSE 0 END) as upvotes,
        SUM(CASE WHEN vote_type = 'downvote' THEN 1 ELSE 0 END) as downvotes
        FROM answer_votes 
        WHERE answer_id = ?";
    $stmt = $conn->prepare($count_query);
    $stmt->bind_param("i", $answer_id);
    $stmt->execute();
    $counts = $stmt->get_result()->fetch_assoc();

    // Get user's current vote status
    $user_vote = null;
    if ($existing_vote) {
        if ($existing_vote['vote_type'] === $vote_type) {
            $user_vote = null; // Vote was removed
        } else {
            $user_vote = $vote_type; // Vote was changed
        }
    } else {
        $user_vote = $vote_type; // New vote
    }

    echo json_encode([
        'success' => true,
        'upvotes' => (int)($counts['upvotes'] ?? 0),
        'downvotes' => (int)($counts['downvotes'] ?? 0),
        'userVote' => $user_vote
    ]);
} catch (Exception $e) {
    die(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
} 
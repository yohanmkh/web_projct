<?php
require 'config.php';

// Get the search query
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Perform the search
$query = "SELECT q.*, u.username FROM questions q 
          JOIN users u ON q.user_id = u.id 
          WHERE q.title LIKE ? OR q.content LIKE ? 
          ORDER BY q.created_at DESC";

$stmt = $conn->prepare($query);
$searchTerm = "%$search%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$questions = array();
while ($question = $result->fetch_assoc()) {
    // Format the date
    $date = new DateTime($question['created_at']);
    $question['formatted_date'] = $date->format('M j, Y \a\t H:i');
    
    // Clean the data for JSON
    $question['title'] = htmlspecialchars($question['title']);
    $question['content'] = htmlspecialchars($question['content']);
    $question['username'] = htmlspecialchars($question['username']);
    
    $questions[] = $question;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($questions);
?> 
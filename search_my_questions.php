<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    exit('Please log in to view your questions.');
}

$user_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$where_clause = "user_id = $user_id";
if (!empty($search)) {
    $where_clause .= " AND (title LIKE '%$search%' OR content LIKE '%$search%')";
}

$query = "SELECT * FROM questions WHERE $where_clause ORDER BY created_at DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while($question = $result->fetch_assoc()) {
        ?>
        <div class="question">
            <h3>
                <a href="view_question.php?id=<?php echo $question['id']; ?>">
                    <?php echo htmlspecialchars($question['title']); ?>
                </a> 
                <a href="edit_question.php?id=<?php echo $question['id']; ?>" class="login_button">
                    <img id="edit_icon" src="images/edit.png" alt="edit" width=26px height=26px>
                </a>
                <a href="delete_question.php?id=<?php echo $question['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this question?')" 
                   style="margin-left: 10px;">
                    <img src="images/delete.png" alt="delete" width=26px height=26px>
                </a>
            </h3>
            <p><?php echo nl2br(htmlspecialchars($question['content'])); ?></p>
            <br>
            <small>Asked on: <?php echo $question['created_at']; ?></small>
        </div>
        <?php
    }
} else {
    echo '<p>' . (empty($search) ? "You haven't asked any questions yet." : "No questions found matching your search.") . '</p>';
}
?> 
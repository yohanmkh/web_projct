<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Add search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_clause = "user_id = $user_id";
if (!empty($search)) {
    $where_clause .= " AND (title LIKE '%$search%' OR content LIKE '%$search%')";
}

$query = "SELECT * FROM questions WHERE $where_clause ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Questions - BugBox</title>
    <link rel="stylesheet" href="styles2.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="main-content">
    <div class="card question-container">
        <h2 style="text-align:center;">My Questions</h2>
        <div id="searchResults" >
            <?php if ($result->num_rows > 0): ?>
                <?php while($question = $result->fetch_assoc()): ?>
                    <div class="question" style="margin-bottom: 70px; border-bottom: 1px solid #ccc; padding-bottom: 20px;">
                        <h3><a href="view_question.php?id=<?php echo $question['id']; ?>">
                            <?php echo htmlspecialchars($question['title']); ?>
                        </a>
                        <a href="edit_question.php?id=<?php echo $question['id']; ?>" class="login_button"><img id="edit_icon" src="images/edit.png" alt="edit" width=26px height=26px></a>
                        <a href="delete_question.php?id=<?php echo $question['id']; ?>" onclick="return confirm('Are you sure you want to delete this question?')" style="margin-left: 10px;"><img src="images/delete.png" alt="delete" width=26px height=26px></a>
                        </h3>
                        <p><?php echo nl2br(htmlspecialchars($question['content'])); ?></p>
                        <br>
                        <small>Asked on: <?php echo $question['created_at']; ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p><?php echo empty($search) ? "You haven't asked any questions yet." : "No questions found matching your search."; ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>

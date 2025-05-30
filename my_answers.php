<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_clause = "a.user_id = $user_id";
if (!empty($search)) {
    $where_clause .= " AND a.content LIKE '%$search%'";
}

$query = "SELECT a.*, q.title 
          FROM answers a 
          JOIN questions q ON a.question_id = q.id 
          WHERE $where_clause 
          ORDER BY a.created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Answers - BugBox</title>
    <link rel="stylesheet" href="styles2.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="main-content">
    <div class="card question-container">
        <h2 style="text-align:center;">My Answers</h2>
        <div id="searchResults">
            <?php if ($result->num_rows > 0): ?>
                <?php while($answer = $result->fetch_assoc()): ?>
                    <div class="question" style="margin-bottom: 70px; border-bottom: 1px solid #ccc; padding-bottom: 20px;">
                        <h3>In response to: 
                            <a href="view_question.php?id=<?php echo $answer['question_id']; ?>">
                                <?php echo htmlspecialchars($answer['title']); ?>
                            </a>
                            <a href="edit_answer.php?id=<?php echo $answer['id']; ?>" class="login_button">
                                <img src="images/edit.png" alt="edit" width="26px" height="26px">
                            </a>
                            <a href="delete_answer.php?id=<?php echo $answer['id']; ?>" onclick="return confirm('Are you sure you want to delete this answer?')" style="margin-left: 10px;">
                                <img src="images/delete.png" alt="delete" width="26px" height="26px">
                            </a>
                        </h3>
                        <p><?php echo nl2br(htmlspecialchars($answer['content'])); ?></p>
                        <br>
                        <small>Answered on: <?php echo $answer['created_at']; ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p><?php echo empty($search) ? "You haven't answered any questions yet." : "No answers found matching your search."; ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>

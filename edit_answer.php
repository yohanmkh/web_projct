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

// Fetch answer and question details
$query = "SELECT a.*, q.id as question_id, q.title as question_title 
          FROM answers a 
          JOIN questions q ON a.question_id = q.id 
          WHERE a.id = $answer_id AND a.user_id = $user_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("Answer not found or you don't have permission to edit it.");
}

$answer = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $content = $conn->real_escape_string($_POST['content']);
    
    $update = "UPDATE answers SET content='$content' WHERE id=$answer_id AND user_id = $user_id";
    
    if ($conn->query($update)) {
        header("Location: view_question.php?id=" . $answer['question_id']);
        exit;
    } else {
        $error = "Failed to update answer.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Answer - BugBox</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="home-page">
<?php include 'navbar.php'; ?>

<div class="main-content">
    <div class="edit-page-container">
        <div class="edit-answer-card">
            <div class="edit-answer-header">
                <h1 class="edit-answer-title">Edit Answer</h1>
                <p class="edit-answer-subtitle">
                    You are editing your answer to the question: 
                    <a href="view_question.php?id=<?php echo $answer['question_id']; ?>">
                        <?php echo htmlspecialchars($answer['question_title']); ?>
                    </a>
                </p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="edit-answer-form">
                <textarea name="content" 
                          class="edit-answer-textarea" 
                          required 
                          placeholder="Enter your answer here..."
                ><?php echo htmlspecialchars($answer['content']); ?></textarea>

                <div class="edit-answer-actions">
                    <button type="submit" class="save-answer-btn">Save Changes</button>
                    <a href="view_question.php?id=<?php echo $answer['question_id']; ?>" 
                       class="cancel-edit-btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html> 
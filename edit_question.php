<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "No question selected.";
    exit;
}

$question_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch question to edit
$query = "SELECT * FROM questions WHERE id = $question_id AND user_id = $user_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "Question not found or you don't have permission to edit it.";
    exit;
}

$question = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    
    $update = "UPDATE questions SET title='$title', content='$content' WHERE id=$question_id AND user_id = $user_id";
    
    if ($conn->query($update)) {
        header("Location: my_questions.php");
        exit;
    } else {
        $error = "Failed to update question.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Question - BugBox</title>
    <link rel="stylesheet" href="styles2.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="main-content">
    <div class="card edit-question-card">
        <h2 class="edit-question-title">Edit Your Question</h2>
        <?php if (!empty($error)) echo "<div class='error-message'>$error</div>"; ?>
        <form method="POST" class="edit-question-form">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($question['title']); ?>" required>
            <label for="content">Content</label>
            <textarea id="content" name="content" rows="7" required><?php echo htmlspecialchars($question['content']); ?></textarea>
            <div class="edit-question-actions">
                <button type="submit" class="save-question-btn">Save Changes</button>
                <a href="my_questions.php" class="cancel-edit-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#navbarSearchForm").submit(function(e){
            e.preventDefault();
            var searchVal = $("#navbarSearchInput").val();
            window.location.href = "index.php?search=" + encodeURIComponent(searchVal);
        });
    });
</script>
</body>
</html>

<?php
// add_question.php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO questions (title, content, user_id, created_at) VALUES ('$title', '$content', '$user_id', NOW())";
    if ($conn->query($query)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Question - BugBox</title>
    <link rel="stylesheet" href="styles2.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="main-content">
    <div class="card question-container">
        <h2 style="text-align:center;">Add Question</h2>
        <?php if(isset($error)) echo "<div class='error-message' style='max-width:320px;margin:12px auto 0 auto;text-align:center;'>$error</div>"; ?>
        <form method="post" action="add_question.php" class="add-question-form" style="display: flex; flex-direction: column; align-items: center; gap: 18px;">
            <div style="width:100%;max-width:400px;display:flex;flex-direction:column;align-items:center;">
                <label for="title" style="text-align:center;">Title</label>
                <input type="text" id="title" name="title" required style="width:100%;max-width:320px;">
            </div>
            <div style="width:100%;max-width:400px;display:flex;flex-direction:column;align-items:center;">
                <label for="content" style="text-align:center;">Content</label>
                <textarea id="content" name="content" rows="5" required style="width:100%;max-width:320px;"></textarea>
            </div>
            <button type="submit" class="submit_button">Post Question</button>
        </form>
        <p style="text-align:center;"><a href="index.php">Back to Questions</a></p>
    </div>
</div>
</body>
</html>

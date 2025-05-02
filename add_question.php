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
    <title>Add Question</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="question-container" >
    <h2>Add Question</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post" action="add_question.php">
        <label>Title:<br>
            <input type="text" name="title" required>
        </label><br>
        <label>Content:<br>
            <textarea name="content" rows="5" required></textarea>
        </label><br>
        <button type="submit">Post Question</button>
    </form>
    <p><a href="index.php">Back to Questions</a></p>
    </div>
</body>
</html>

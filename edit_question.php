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
<html>
<head>
    <title>Edit Question</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="navbar">
  <div class="logo-section">
    <img src="images/bugicon.png" alt="BugBox Logo" class="logo-img">
    <span class="logo-text">BugBox</span>
  </div>
  <div class="search-container">
    <input type="text" class="search-input" placeholder="Search...">
    <button class="search-btn">🔍</button>
  </div>
  <nav class="nav-links">
    <a href="index.php">Home</a>
    <a href="my_questions.php">Questions</a>
    <a href="logout.php">Logout</a>
   
  </nav>
</header>
    <div class="edit-container">
        <h2>Edit Your Question</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="title" class="cred_input" value="<?php echo htmlspecialchars($question['title']); ?>" required><br>
            <textarea name="content" rows="7" class="cred_input" required><?php echo htmlspecialchars($question['content']); ?></textarea><br>
            <button type="submit" class="submit_button">Save Changes</button>
        </form>
    </div>
</body>
</html>

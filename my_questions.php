<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM questions WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Questions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div>
    <header class="navbar">
        <div class="logo-section">
            <img src="images/bugicon.png" alt="BugBox Logo" class="logo-img">
            <span class="logo-text">BugBox</span>
        </div>
        <nav class="nav-links">
            <a href="index.php">Home</a>
            
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    </div>
    <div class="question-container">
        <h2>My Questions
            
        </h2>
        
        <?php if ($result->num_rows > 0): ?>
            <?php while($question = $result->fetch_assoc()): ?>
                <div class="question">
                    <h3><a href="view_question.php?id=<?php echo $question['id']; ?>">
                        <?php echo htmlspecialchars($question['title']); ?>
                    </a> <a href="edit_question.php?id=<?php echo $question['id']; ?>" class="login_button"><img  id="edit_icon"src="images/edit.png" alt="edit" width=26px height=26px></a>
                    </h3>
                    <p><?php echo nl2br(htmlspecialchars($question['content'])); ?></p>
                    <br>
                    <small>Asked on: <?php echo $question['created_at']; ?></small>
                    
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You haven't asked any questions yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>

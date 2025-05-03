<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$query = "SELECT a.*, q.title FROM answers a 
          JOIN questions q ON a.question_id = q.id 
          WHERE a.user_id = ?
          ORDER BY a.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Answers</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>My Answers</h1>

<?php while($row = $result->fetch_assoc()): ?>
    <div class="answer-box">
        <h3>In response to: 
            <a href="view_question.php?id=<?php echo $row['question_id']; ?>">
                <?php echo htmlspecialchars($row['title']); ?>
            </a>
        </h3>
        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
        <small>Answered on: <?php echo $row['created_at']; ?></small>
        <hr>
    </div>
<?php endwhile; ?>

</body>
</html>

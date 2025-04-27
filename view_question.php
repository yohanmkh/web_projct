<?php
// view_question.php
require 'config.php';
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$question_id = intval($_GET['id']);
$query = "SELECT q.*, u.username FROM questions q JOIN users u ON q.user_id = u.id WHERE q.id = $question_id";
$q_result = $conn->query($query);
if ($q_result->num_rows == 0) {
    die("Question not found.");
}
$question = $q_result->fetch_assoc();

// Fetch answers for this question
$query = "SELECT a.*, u.username FROM answers a JOIN users u ON a.user_id = u.id WHERE a.question_id = $question_id ORDER BY created_at ASC";
$answers = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($question['title']); ?></title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function rateAnswer(answerId, rating, button) {
        // Disable the button immediately to prevent multiple clicks
        button.disabled = true;

        $.ajax({
            url: 'rate_answer.php',
            type: 'POST',
            data: { answer_id: answerId, rating: rating },
            success: function(response) {
                // Update the rating display
                $("#rating-" + answerId).text(response);
            },
            error: function() {
                // Re-enable the button if the request fails
                button.disabled = false;
                alert("An error occurred. Please try again.");
            }
        });
    }
</script>
</head>
<body>

    <div class="question">
    <h2><?php echo htmlspecialchars($question['title']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($question['content'])); ?></p>
    <p><small>Asked by <?php echo htmlspecialchars($question['username']); ?> on <?php echo $question['created_at']; ?></small></p>
    <hr>
    <h3>Answers</h3>
    <?php while($answer = $answers->fetch_assoc()): ?>
        <div class="answer">
            <p><?php echo nl2br(htmlspecialchars($answer['content'])); ?></p>
            <p><small>Answered by <?php echo htmlspecialchars($answer['username']); ?> on <?php echo $answer['created_at']; ?></small></p>
            <p>Rating: <span id="rating-<?php echo $answer['id']; ?>"><?php echo $answer['rating']; ?></span></p>
            <?php if(isset($_SESSION['user_id']) ): ?>
               
                <button name="answer_id" onclick="rateAnswer(<?php echo $answer['id']; ?>, 1,this)">Upvote</button>
                <button onclick="rateAnswer(<?php echo $answer['id']; ?>, -1,this)">Downvote</button>
                
                <?php endif; ?>

        </div>
    <?php endwhile; ?>
    <?php if(isset($_SESSION['user_id'])): ?>
    <hr>
    <h3>Add Answer</h3>
    <form method="post" action="add_answer.php">
        <textarea name="content" rows="5" required></textarea>
        <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
        <button type="submit">Post Answer</button>
    </form>
    <?php else: ?>
        <p>Please <a href="login.php">login</a> to post an answer.</p>
    <?php endif; ?>
    <p><a href="index.php">Back to Questions</a></p>
    </div>
</body>
</html>

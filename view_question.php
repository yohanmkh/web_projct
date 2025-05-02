<?php
// view_question.php
require 'config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$question_id = $conn->real_escape_string($_GET['id']);
$query = "SELECT q.*, u.username FROM questions q 
          JOIN users u ON q.user_id = u.id 
          WHERE q.id = '$question_id'";
$result = $conn->query($query);
$question = $result->fetch_assoc();

if (!$question) {
    header("Location: index.php");
    exit;
}

// Get answers with vote counts
$answers_query = "SELECT 
    a.*, 
    u.username,
    COALESCE(SUM(CASE WHEN av.vote_type = 'upvote' THEN 1 ELSE 0 END), 0) as upvotes,
    COALESCE(SUM(CASE WHEN av.vote_type = 'downvote' THEN 1 ELSE 0 END), 0) as downvotes,
    (SELECT vote_type FROM answer_votes WHERE answer_id = a.id AND user_id = ?) as user_vote
    FROM answers a 
    JOIN users u ON a.user_id = u.id 
    LEFT JOIN answer_votes av ON a.id = av.answer_id
    WHERE a.question_id = ?
    GROUP BY a.id
    ORDER BY (
        COALESCE(SUM(CASE WHEN av.vote_type = 'upvote' THEN 1 ELSE 0 END), 0) -
        COALESCE(SUM(CASE WHEN av.vote_type = 'downvote' THEN 1 ELSE 0 END), 0)
    ) DESC, a.created_at ASC";

$stmt = $conn->prepare($answers_query);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$stmt->bind_param("ii", $user_id, $question_id);
$stmt->execute();
$answers_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($question['title']); ?> - BugBox</title>
    <link rel="stylesheet" href="styles2.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function(){
        let searchTimeout;
        
        function performSearch(searchVal) {
            if (searchVal.trim() !== '') {
                window.location.href = "index.php?search=" + encodeURIComponent(searchVal);
            } else {
                window.location.href = "index.php";
            }
        }

        // Handle form submission
        $("#navbarSearchForm").submit(function(e){
            e.preventDefault();
            var searchVal = $(this).find('input[type="text"]').val();
            performSearch(searchVal);
        });

        // Handle instant search
        $("#navbarSearchInput").on('input', function() {
            clearTimeout(searchTimeout);
            var searchVal = $(this).val();
            
            searchTimeout = setTimeout(function() {
                performSearch(searchVal);
            }, 500); // Wait for 500ms after user stops typing
        });
    });
    </script>
</head>
<body class="home-page">
<?php include 'navbar.php'; ?>

<div class="main-content">
    <div class="card question-view">
        <div class="question-header">
            <div class="question-title-section">
                <h1 class="question-title"><?php echo htmlspecialchars($question['title']); ?></h1>
                <div class="question-metadata">
                    Asked by <?php echo htmlspecialchars($question['username']); ?> on 
                    <?php echo date('M j, Y \a\t H:i', strtotime($question['created_at'])); ?>
                </div>
            </div>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $question['user_id']): ?>
                <div class="action-buttons">
                    <a href="edit_question.php?id=<?php echo $question['id']; ?>" class="action-btn edit-btn" title="Edit this question">
                        ✏️ Edit
                    </a>
                    <a href="delete_question.php?id=<?php echo $question['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this question?')" title="Delete this question">
                        🗑️ Delete
                    </a>
                </div>
            <?php elseif(!isset($_SESSION['user_id'])): ?>
                <div class="action-buttons">
                    <a href="login.php" class="action-btn edit-btn" title="Login to edit questions">✏️ Edit</a>
                    <a href="login.php" class="action-btn delete-btn" title="Login to delete questions">🗑️ Delete</a>
                </div>
            <?php endif; ?>
        </div>
        <div class="question-content">
            <?php echo nl2br(htmlspecialchars($question['content'])); ?>
        </div>
    </div>

    <div class="answers-section">
        <div class="answers-header">
            <h2 class="answers-count">
                <?php echo $answers_result->num_rows; ?> Answer<?php echo $answers_result->num_rows != 1 ? 's' : ''; ?>
            </h2>
        </div>
        <?php if ($answers_result->num_rows > 0): ?>
            <?php while($answer = $answers_result->fetch_assoc()): ?>
                <div class="answer-card answer" data-answer-id="<?php echo $answer['id']; ?>">
                    <div class="vote-buttons">
                        <button class="vote-button upvote<?php echo ($answer['user_vote'] === 'upvote') ? ' active' : ''; ?>">👍</button>
                        <span class="vote-count" title="<?php echo $answer['upvotes']; ?> upvotes, <?php echo $answer['downvotes']; ?> downvotes">
                            <?php echo $answer['upvotes'] - $answer['downvotes']; ?>
                        </span>
                        <button class="vote-button downvote<?php echo ($answer['user_vote'] === 'downvote') ? ' active' : ''; ?>">👎</button>
                    </div>
                    <div style="flex:1;">
                        <div class="answer-content">
                            <?php echo nl2br(htmlspecialchars($answer['content'])); ?>
                        </div>
                        <div class="answer-metadata">
                            Answered by <?php echo htmlspecialchars($answer['username']); ?> on 
                            <?php echo date('M j, Y \a\t H:i', strtotime($answer['created_at'])); ?>
                        </div>
                        <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $answer['user_id']): ?>
                            <div class="action-buttons">
                                <a href="edit_answer.php?id=<?php echo $answer['id']; ?>" class="action-btn edit-btn" title="Edit this answer">
                                    ✏️ Edit
                                </a>
                                <a href="delete_answer.php?id=<?php echo $answer['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this answer?')" title="Delete this answer">
                                    🗑️ Delete
                                </a>
                            </div>
                        <?php elseif(!isset($_SESSION['user_id'])): ?>
                            <div class="action-buttons">
                                <a href="login.php" class="action-btn edit-btn" title="Login to edit answers">✏️ Edit</a>
                                <a href="login.php" class="action-btn delete-btn" title="Login to delete answers">🗑️ Delete</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-answers">No answers yet. Be the first to answer!</p>
        <?php endif; ?>
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="card answer-form">
                <h2>Your Answer</h2>
                <form method="post" action="add_answer.php">
                    <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                    <textarea name="content" required placeholder="Write your answer here..."></textarea>
                    <button type="submit" class="submit-answer-btn">Post Your Answer</button>
                </form>
            </div>
        <?php else: ?>
            <div class="card answer-form" style="text-align:center;">
                <h2>Your Answer</h2>
                <p><a href="login.php" class="submit-answer-btn">Login to post an answer</a></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.vote-button').click(function() {
        if (!$(this).prop('disabled')) {
            const button = $(this);
            const answer = button.closest('.answer');
            const answerId = answer.data('answer-id');
            const voteType = button.hasClass('upvote') ? 'upvote' : 'downvote';
            
            $.ajax({
                url: 'handle_vote.php',
                type: 'POST',
                data: {
                    answer_id: answerId,
                    vote_type: voteType
                },
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                        return;
                    }
                    
                    // Update vote count
                    const voteCount = response.upvotes - response.downvotes;
                    const voteCountElement = answer.find('.vote-count');
                    voteCountElement.text(voteCount);
                    voteCountElement.attr('title', `${response.upvotes} upvotes, ${response.downvotes} downvotes`);
                    
                    // Update button states
                    const upvoteBtn = answer.find('.upvote');
                    const downvoteBtn = answer.find('.downvote');
                    
                    upvoteBtn.removeClass('active');
                    downvoteBtn.removeClass('active');
                    
                    if (response.userVote === 'upvote') {
                        upvoteBtn.addClass('active');
                    } else if (response.userVote === 'downvote') {
                        downvoteBtn.addClass('active');
                    }
                },
                error: function() {
                    alert('Error processing vote. Please try again.');
                }
            });
        }
    });
});
</script>
</body>
</html>

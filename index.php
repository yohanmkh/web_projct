<?php
// index.php
require 'config.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
if ($search !== '') {
    $query = "SELECT q.*, u.username FROM questions q 
              JOIN users u ON q.user_id = u.id 
              WHERE q.title LIKE '%$search%' OR q.content LIKE '%$search%'
              ORDER BY q.created_at DESC";
} else {
    $query = "SELECT q.*, u.username FROM questions q 
              JOIN users u ON q.user_id = u.id 
              ORDER BY q.created_at DESC";
}
$result = $conn->query($query);
if (!$result) {
    die("Error executing query: " . $conn->error);
}

$questions = [];
if ($result->num_rows > 0) {
    while($question = $result->fetch_assoc()) {
        $questions[] = [
            'id' => $question['id'],
            'title' => htmlspecialchars($question['title']),
            'content' => nl2br(htmlspecialchars($question['content'])),
            'username' => htmlspecialchars($question['username']),
            'created_at' => $question['created_at'],
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BugBox - Home</title>
    <link rel="stylesheet" href="styles2.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    const allQuestions = <?php echo json_encode($questions); ?>;
    function renderQuestions(questions) {
        const questionContainer = $('#questionsList');
        questionContainer.empty();
        if (questions.length === 0) {
            questionContainer.append('<div class="question"><p>No questions found.</p></div>');
        } else {
            questions.forEach(function(question) {
                questionContainer.append(
                    `<div class="question">
                        <h3><a href="view_question.php?id=${question.id}">${question.title}</a></h3>
                        <p>${question.content}</p>
                        <br>
                        <small>Asked by ${question.username} on ${question.created_at}</small>
                    </div>`
                );
            });
        }
    }
    $(document).ready(function(){
        // Initial render
        renderQuestions(allQuestions);
        // Prevent form submission from reloading the page
        $("#navbarSearchForm").on('submit', function(e) {
            e.preventDefault();
        });
        // Instant frontend search
        $("#navbarSearchInput").on('input', function() {
            let searchTerm = $(this).val().trim().toLowerCase();
            const filtered = allQuestions.filter(q =>
                q.title.toLowerCase().includes(searchTerm) ||
                q.content.toLowerCase().includes(searchTerm)
            );
            renderQuestions(filtered);
        });
    });
    </script>
    <style>
    #searchSpinner {
        display: none;
        width: 20px;
        height: 20px;
        margin-left: 10px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .error {
        color: #dc3545;
        padding: 10px;
        margin: 10px 0;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
    }
    </style>
</head>
<body class="home-page">
<?php include 'navbar.php'; ?>

<div class="main-content">
    <div class="card welcome-container">
        <header>
            <?php if(isset($_SESSION['user_id'])): ?>
                <img src="images/bugicon.png" alt="BugBox Icon" class="welcome-icon" width="64" height="64">
                <h1>Welcome To BugBox, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                <p>Find answers to your technical questions and help others answer theirs.</p>
            <?php else: ?>
                <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
            <?php endif; ?>
        </header>
    </div>
    <div class="card question-container" id="indexquestion">
        <section>
            <h2>Questions</h2>
            <?php if(isset($_SESSION['user_id'])): ?>
                <button class="submit_button" onclick="window.location.href='add_question.php'">Add Question</button>
            <?php endif; ?>
        </section>

        <div id="questionsList"></div>
    </div>
</div>
</body>
</html>

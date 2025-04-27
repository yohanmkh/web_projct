

<?php
// index.php
require 'config.php';

$search = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $query = "SELECT * FROM questions WHERE title LIKE '%$search%' ORDER BY created_at DESC";
} else {
    $query = "SELECT * FROM questions ORDER BY created_at DESC";
}
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BugBox</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Using jQuery from a CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function(){
        $("#searchForm").submit(function(e){
            e.preventDefault();
            var searchVal = $("#searchInput").val();
            window.location.href = "index.php?search=" + encodeURIComponent(searchVal);
        });
    });
    </script>
</head>
<body class="home-page">
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
<div class="welcome-container">
    <header>
        <?php if(isset($_SESSION['user_id'])): ?>
            <img src="images/bugs.png" alt="bugsearch" class="bugsearch" width="80" height="80">
            <h1>Welcome To BugBox , <?php echo htmlspecialchars($_SESSION['username']); ?> </h1>
            <p>Find answers to your technical questions and help others answer theirs.</p>
        <?php else: ?>
            <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
        <?php endif; ?>
    </header>
    </div>
    <div class="question-container" id="indexquestion">
    <section >
        <form id="searchForm">
            <input type="text" id="searchInput" name="search" placeholder="Search questions..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
        <h2>Questions</h2>
        <button class="submit_button" onclick="window.location.href='add_question.php'">Add Question</button>    <?php while($question = $result->fetch_assoc()): ?>

            </section>
           
    
    </div>
    <div class="question">
                <h3><a href="view_question.php?id=<?php echo $question['id']; ?>">
                    <?php echo htmlspecialchars($question['title']); ?>
                </a></h3>
                
                <p><?php echo nl2br(htmlspecialchars($question['content'])); ?></p>
                <br>
                <small>Asked on: <?php echo $question['created_at']; ?></small>
            </div>
            
        <?php endwhile; ?>
</body>
</html>

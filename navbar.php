<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="navbar">
    <div class="logo-section">
        <img src="images/logo.png " alt="BugBox Icon" class="logo-img" >
        <span class="logo-text">BugBox</span>
    </div>
    <div class="search-container">
        <form id="navbarSearchForm" style="display: flex; width: 100%;">
            <input type="text" class="search-input" id="navbarSearchInput" 
                   placeholder="Search questions..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <div id="searchSpinner"></div>
        </form>
    </div>
    <nav class="nav-links">
        <a href="index.php">Home</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="my_questions.php">My Questions</a>
            <a href="my_answers.php">My Answers</a>
            <a href="add_question.php">Ask Question</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

<script>
// The following assumes a global allQuestions array and a renderQuestions function are available on the page.
$(document).ready(function(){
    // Prevent form submission from reloading the page
    $("#navbarSearchForm").on('submit', function(e) {
        e.preventDefault();
        let searchTerm = $("#navbarSearchInput").val().trim();
        if (typeof allQuestions !== 'undefined' && typeof renderQuestions === 'function') {
            const filtered = allQuestions.filter(q =>
                q.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                q.content.toLowerCase().includes(searchTerm.toLowerCase())
            );
            renderQuestions(filtered);
        } else {
            // Redirect to index.php with search term
            window.location.href = 'index.php?search=' + encodeURIComponent(searchTerm);
        }
    });
    // Instant frontend search or redirect on input
    $("#navbarSearchInput").on('input', function(e) {
        let searchTerm = $(this).val().trim();
        if (typeof allQuestions !== 'undefined' && typeof renderQuestions === 'function') {
            const filtered = allQuestions.filter(q =>
                q.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                q.content.toLowerCase().includes(searchTerm.toLowerCase())
            );
            renderQuestions(filtered);
        }
    });
});
</script> 
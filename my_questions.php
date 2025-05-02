<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Add search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_clause = "user_id = $user_id";
if (!empty($search)) {
    $where_clause .= " AND (title LIKE '%$search%' OR content LIKE '%$search%')";
}

$query = "SELECT * FROM questions WHERE $where_clause ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Questions</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="question-container">
    <h2>My Questions</h2>
    
    <div class="search-container">
        <form id="navbarSearchForm" style="display: flex; width: 100%;">
            <input type="text" class="search-input" id="navbarSearchInput" placeholder="Search questions..." value="<?php echo htmlspecialchars($search); ?>">
        </form>
    </div>
    
    <div id="searchResults">
        <?php if ($result->num_rows > 0): ?>
            <?php while($question = $result->fetch_assoc()): ?>
                <div class="question">
                    <h3><a href="view_question.php?id=<?php echo $question['id']; ?>">
                        <?php echo htmlspecialchars($question['title']); ?>
                    </a> 
                    <a href="edit_question.php?id=<?php echo $question['id']; ?>" class="login_button"><img id="edit_icon" src="images/edit.png" alt="edit" width=26px height=26px></a>
                    <a href="delete_question.php?id=<?php echo $question['id']; ?>" onclick="return confirm('Are you sure you want to delete this question?')" style="margin-left: 10px;"><img src="images/delete.png" alt="delete" width=26px height=26px></a>
                    </h3>
                    <p><?php echo nl2br(htmlspecialchars($question['content'])); ?></p>
                    <br>
                    <small>Asked on: <?php echo $question['created_at']; ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p><?php echo empty($search) ? "You haven't asked any questions yet." : "No questions found matching your search."; ?></p>
        <?php endif; ?>
    </div>
</div>
<script>
    $(document).ready(function(){
        let searchTimeout;
        
        function performSearch(searchVal) {
            $.ajax({
                url: 'search_my_questions.php',
                type: 'GET',
                data: { search: searchVal },
                beforeSend: function() {
                    $('#searchResults').html('<div class="loading">Searching...</div>');
                },
                success: function(response) {
                    $('#searchResults').html(response);
                },
                error: function() {
                    $('#searchResults').html('<div class="error">Error performing search. Please try again.</div>');
                }
            });
        }

        // Handle form submission
        $("#navbarSearchForm").submit(function(e){
            e.preventDefault();
            var searchVal = $("#navbarSearchInput").val();
            performSearch(searchVal);
        });

        // Handle instant search
        $("#navbarSearchInput").on('input', function() {
            clearTimeout(searchTimeout);
            var searchVal = $(this).val();
            
            searchTimeout = setTimeout(function() {
                performSearch(searchVal);
            }, 300); // Reduced to 300ms for faster response
        });
    });
</script>
</body>
</html>

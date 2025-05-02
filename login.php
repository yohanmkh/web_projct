<?php
// login.php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid credentials";
        }
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - BugBox</title>
    <link rel="stylesheet" href="styles2.css">
    <link rel="icon" type="image/x-icon" href="images/bug.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>
<script>
$(document).ready(function(){
    // Hide the search bar on the login page
    $(".search-container").hide();
});
</script>
<div class="main-content">
    <div class="card login-container">
        <h2 class="login-title" style="text-align: center;">Login</h2>
        <?php if (!empty($error)) echo "<div class='error-message'>$error</div>"; ?>
        <form method="POST" class="login-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <div class="login-actions" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                <button type="submit" class="submit_button">Login</button>
                <a href="register.php" class="cancel-edit-btn">Don't have an account? Register</a>
            </div>
        </form>
        <div class="google-login-separator" style="margin: 24px 0 12px; text-align: center; color: #aaa;">or</div>
        <button class="google-btn" id="googleLoginBtn" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo" class="google-icon" style="width: 22px; height: 22px;" />
            Continue with Google
        </button>
    </div>
</div>
<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
  import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-auth.js";

  const firebaseConfig = {
    apiKey: "AIzaSyDWjotof-IC0tv4sQRPFI-4Fsk4ImaHy9s",
    authDomain: "bugbox-e3146.firebaseapp.com",
    projectId: "bugbox-e3146",
    storageBucket: "bugbox-e3146.appspot.com",
    messagingSenderId: "87393665458",
    appId: "1:87393665458:web:1ba12781e3e2b7960f24ab",
    measurementId: "G-TPK764XLD9"
  };

  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  const provider = new GoogleAuthProvider();

  window.addEventListener('DOMContentLoaded', () => {
    const googleBtn = document.getElementById("googleLoginBtn");
    googleBtn.addEventListener("click", () => {
      signInWithPopup(auth, provider)
        .then((result) => {
          const user = result.user;
          fetch('google_register.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              email: user.email,
              name: user.displayName,
              uid: user.uid
            })
          })
          .then(res => res.text())
          .then(msg => {
          
            window.location.href = "index.php";
          });
        })
        .catch((error) => {
          console.error("Google login failed:", error);
        });
    });
  });
</script>

</body>
</html>

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
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="images/bug.ico">
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
    <a href="questions.php">Questions</a>
    <a href="add">Ask</a>
    <a href="about.html"></a>
   
  </nav>
</header>
<div style="display: flex ;flex-direction: column; align-items: center; justify-content: center; height: 100vh; background-color: #f0f0f0;">
 
<div>      
    <img class="logo" src="images/bugicon.png" alt="logo" >
</div>


<div class="login-container">
    
    
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post" action="login.php">
        <div class="labels">
        <label>Username:<br>
            <input class=cred_input type="text" name="username" required>
        </label><br>
        </div>
        <div class="labels">
        <label>Password:<br>
            <input   class=cred_input  type="password" name="password" required>
        </label><br>
        </div>
        <button class="submit_button" type="submit">Login</button>
    </form>
    <p><a class="donts" href="register.php">Don't have an account? Register here</a></p>
</div>
<div>
  <button class="google-btn" id="googleLoginBtn">
  <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo" class="google-icon" />
  Continue with Google
</button>

    
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

<?php
// register.php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if ($conn->query($query)) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
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
    <a href="index.html">Home</a>
    <a href="questions.html">Questions</a>
    <a href="ask.html">Ask</a>
    <a href="about.html"></a>
  </nav>
</header>  

<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; background-color: #f2f2f2;">
  <div>      
    <img class="logo" src="images/bugicon.png" alt="logo">
  </div>

  <div class="register-container">
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post" action="register.php">
        <label>Username:<br>
            <input class="cred_input" type="text" name="username" required>
        </label><br>
        <label>Password:<br>
            <input class="cred_input" type="password" name="password" required>
        </label><br>
        <button class="submit_button" type="submit">Register</button>
    </form>
    <p><a class="donts" href="login.php">Already have an account? Login here</a></p>
  </div>

  <div>
  <button class="google-btn" id="googleLoginBtn">
  <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo" class="google-icon" />
  Continue with Google
</button>

    
     </div>
</div>

<!-- Firebase Configuration and Google Sign-in -->
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

    // Attach event listener after DOM is ready
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
                        alert("Logged in with Google: " + msg);
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

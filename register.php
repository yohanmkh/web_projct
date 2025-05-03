<?php
// register.php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    // Check if username already exists
    $check = $conn->query("SELECT id FROM users WHERE username = '$username' LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $error = "Username already exists. Please choose another.";
    } else {
        // Password validation
        $errors = [];
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        }
        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
            $errors[] = "Password must contain at least one special character";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number";
        }

        if (empty($errors)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
            if ($conn->query($query)) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Error: " . $conn->error;
            }
        } else {
            $error = implode("<br>", $errors);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - BugBox</title>
    <link rel="stylesheet" href="styles2.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="main-content">
    <div class="card register-container">
        <h2 class="register-title" style="text-align: center;">Register</h2>
        <form method="POST" class="register-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <ul id="password-requirements" style="font-size: 0.95em; color: #666; margin: 8px 0 0 0; padding-left: 0; list-style: disc; list-style-position: inside; text-align: center;">
                <li id="length">At least 8 characters</li>
                <li id="uppercase">One uppercase letter</li>
                <li id="lowercase">One lowercase letter</li>
                <li id="special">One special character</li>
                <li id="number">One number</li>
            </ul>
            <?php if (!empty($error)) echo "<div class='error-message' style='max-width:320px;margin:12px auto 0 auto;text-align:center;'>$error</div>"; ?>
            <div class="register-actions" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                <button type="submit" class="submit_button">Register</button>
                <a href="login.php" class="cancel-edit-btn">Already have an account? Login</a>
            </div>
        </form>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const requirements = {
        length: document.getElementById('length'),
        uppercase: document.getElementById('uppercase'),
        lowercase: document.getElementById('lowercase'),
        number: document.getElementById('number'),
        special: document.getElementById('special')
    };

    password.addEventListener('input', function() {
        const value = this.value;
        
        // Check length
        if (value.length >= 8) {
            requirements.length.style.color = 'green';
        } else {
            requirements.length.style.color = '#666';
        }

        // Check uppercase
        if (/[A-Z]/.test(value)) {
            requirements.uppercase.style.color = 'green';
        } else {
            requirements.uppercase.style.color = '#666';
        }

        // Check lowercase
        if (/[a-z]/.test(value)) {
            requirements.lowercase.style.color = 'green';
        } else {
            requirements.lowercase.style.color = '#666';
        }

        // Check number
        if (/[0-9]/.test(value)) {
            requirements.number.style.color = 'green';
        } else {
            requirements.number.style.color = '#666';
        }

        // Check special character
        if (/[!@#$%^&*()\-_=+{};:,<.>]/.test(value)) {
            requirements.special.style.color = 'green';
        } else {
            requirements.special.style.color = '#666';
        }
    });
});
</script>

<script>
$(document).ready(function(){
    // Hide the search bar on the register page
    $(".search-container").hide();
});
</script>

</body>
</html>

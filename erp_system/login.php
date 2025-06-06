<?php
session_start();
include('db.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid credentials.";
        }
    } else {
        $error = "User not found.";
    }
}

// Handle registration
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    $new_username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $role = $_POST['role'];

    // Validate Admin Password Rule
    if ($role === "admin" && !str_ends_with($new_password, "admin")) {
        $error = "Admin passwords must end with 'admin'.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Check if username exists
        $check_user = mysqli_query($conn, "SELECT * FROM users WHERE username='$new_username'");
        if (mysqli_num_rows($check_user) > 0) {
            $error = "Username already exists.";
        } else {
            $insert_query = "INSERT INTO users (username, password, role) VALUES ('$new_username', '$hashed_password', '$role')";
            if (mysqli_query($conn, $insert_query)) {
                $success = "Account created successfully! You can now log in.";
            } else {
                $error = "Registration failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login & Signup</title>
    <style>
        body { background-color: #1E1E1E; color: white; text-align: center; font-family: Arial, sans-serif; }
        form { display: inline-block; padding: 20px; background: #252525; border-radius: 10px; }
        input, select, button { padding: 10px; margin: 10px; }
        button { background: #00ADB5; color: white; cursor: pointer; }
        .password-container { position: relative; }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #00ADB5;
        }
    </style>
    <script>
        function togglePassword(id) {
            let input = document.getElementById(id);
            let toggle = document.getElementById(id + "-toggle");
            if (input.type === "password") {
                input.type = "text";
                toggle.textContent = "Hide";
            } else {
                input.type = "password";
                toggle.textContent = "Show";
            }
        }
    </script>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <div class="password-container">
            <input type="password" name="password" id="password-login" placeholder="Password" required>
            <span class="toggle-password" id="password-login-toggle" onclick="togglePassword('password-login')">Show</span>
        </div>
        <button type="submit" name="login">Login</button>
    </form>

    <hr>

    <h2>Sign Up</h2>
    <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
    <form method="POST">
        <input type="text" name="new_username" placeholder="New Username" required><br>
        <div class="password-container">
            <input type="password" name="new_password" id="password-signup" placeholder="New Password" required>
            <span class="toggle-password" id="password-signup-toggle" onclick="togglePassword('password-signup')">Show</span>
        </div>
        <select name="role">
            <option value="staff">Staff</option>
            <option value="admin">Admin</option>
        </select><br>
        <button type="submit" name="register">Sign Up</button>
    </form>
</body>
</html>
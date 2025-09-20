<?php
// admin_register.php
include("config.php");

// If already logged in as admin, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic input capture + trimming
    $name = trim(mysqli_real_escape_string($conn, $_POST['name'] ?? ''));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Simple validation
    if ($name === '' || $email === '' || $password === '') {
        $msg = "Please fill all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Please enter a valid email address.";
    } elseif ($password !== $confirm_password) {
        $msg = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            $msg = "An account with this email already exists.";
        } else {
            // Hash the password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert admin user
            $sql = "INSERT INTO users (name, email, password, role) 
                    VALUES ('$name', '$email', '$hashed', 'admin')";

            if (mysqli_query($conn, $sql)) {
                // Auto-login the newly created admin
                $admin_id = mysqli_insert_id($conn);
                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['admin_name'] = $name;
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $msg = "Database error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; display:flex; align-items:center; justify-content:center; height:100vh; }
        .box { background:#fff; padding:24px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.08); width:360px; }
        input { width:100%; padding:10px; margin:8px 0; box-sizing:border-box; }
        button { width:100%; padding:10px; background:#007BFF; color:#fff; border:none; border-radius:4px; }
        .msg { color:red; margin-bottom:10px; }
        .note { font-size:12px; color:#555; margin-top:10px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Create Admin Account</h2>
    <?php if ($msg) echo "<div class='msg'>" . htmlspecialchars($msg) . "</div>"; ?>
    <form method="post">
        <input type="text" name="name" placeholder="Full name" required>
        <input type="email" name="email" placeholder="Email address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm password" required>
        <button type="submit">Create Admin</button>
    </form>

    <p class="note">
        <strong>Security:</strong> Remove or secure this file after creating admin accounts, otherwise anyone can make an admin.
    </p>
    <p class="note">
        Already have an admin? <a href="admin_login.php">Login here</a>.
    </p>
</div>
</body>
</html>

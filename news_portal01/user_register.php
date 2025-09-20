<?php
// user_register.php
include("config.php");

// If user already logged in → go to home
if (isset($_SESSION['user_id'])) {
    header("Location: user_home.php");
    exit();
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $msg = "❌ Passwords do not match!";
    } else {
        // Check if email already exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $msg = "❌ Email already registered. Please use another.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $sql = "INSERT INTO users (name, email, password, role) 
                    VALUES ('$name', '$email', '$hashed_password', 'user')";

            if (mysqli_query($conn, $sql)) {
                $msg = "✅ Registration successful! <a href='user_login.php' class='underline text-blue-600'>Login here</a>";
            } else {
                $msg = "❌ Error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">

<div class="w-full max-w-md bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-center mb-6">📰 User Registration</h2>

    <?php if ($msg): ?>
        <p class="mb-4 text-center 
            <?php echo (strpos($msg, '✅') !== false) ? 'text-green-600' : 'text-red-600'; ?>">
            <?= $msg ?>
        </p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <input type="text" name="name" placeholder="Full Name" required 
               class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 outline-none">
        
        <input type="email" name="email" placeholder="Email Address" required 
               class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 outline-none">
        
        <input type="password" name="password" placeholder="Password" required 
               class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 outline-none">
        
        <input type="password" name="confirm_password" placeholder="Confirm Password" required 
               class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 outline-none">

        <button type="submit" 
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition">
            Register
        </button>
    </form>

    <p class="text-center mt-4 text-gray-600">
        Already have an account? 
        <a href="user_login.php" class="text-blue-600 hover:underline">Login here</a>
    </p>
</div>

</body>
</html>

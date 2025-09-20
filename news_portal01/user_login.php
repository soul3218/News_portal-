<?php
// user_login.php
include("config.php");

// If user already logged in → go to home
if (isset($_SESSION['user_id'])) {
    header("Location: user_home.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE email='$email' AND role='user' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            header("Location: user_home.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user account found with this email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center min-h-screen">

  <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">👤 User Login</h2>

    <?php if ($error) { ?>
      <p class="mb-4 text-red-600 font-medium text-center"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST" class="space-y-4">
      <input type="email" name="email" placeholder="User Email" required
             class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />

      <input type="password" name="password" placeholder="Password" required
             class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />

      <button type="submit" 
              class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
        Login
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
      Don't have an account? 
      <a href="user_register.php" class="text-blue-600 hover:underline font-medium">Register here</a>
    </p>
  </div>

</body>
</html>

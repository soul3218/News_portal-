<?php
// admin_login.php
include("config.php");

// If admin already logged in → go to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if admin exists
    $sql = "SELECT * FROM users WHERE email='$email' AND role='admin' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No admin account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8">
      <!-- Header -->
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">🔑 Admin Login</h2>

      <!-- Error Message -->
      <?php if ($error): ?>
          <div class="mb-4 text-red-600 bg-red-100 border border-red-300 p-3 rounded-lg text-sm">
              <?= $error ?>
          </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form method="POST" class="space-y-4">
          <div>
              <input type="email" name="email" placeholder="Admin Email" required
                     class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
          </div>
          <div>
              <input type="password" name="password" placeholder="Password" required
                     class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
          </div>
          <button type="submit" 
                  class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-300">
              Login
          </button>
      </form>

      <!-- Footer -->
      <p class="text-gray-500 text-xs text-center mt-6">
          © <?= date("Y") ?> News Portal | Admin Access Only
      </p>
  </div>

</body>
</html>

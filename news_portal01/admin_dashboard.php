<?php
// admin_dashboard.php
include("config.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch total news
$newsCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM news"))['total'];

// Fetch total comments
$commentCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM comments"))['total'];

// Fetch total admins
$adminCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='admin'"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex min-h-screen">

  <!-- Sidebar -->
  <aside class="w-64 bg-blue-800 text-white flex flex-col">
    <div class="p-6 border-b border-blue-700">
      <h1 class="text-2xl font-bold tracking-wide">Admin Dashboard </h1>
      <p class="text-sm text-blue-200 mt-1">Manage your portal</p>
    </div>
    <nav class="flex-1 p-4 space-y-2">
      <a href="admin_dashboard.php" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-blue-700">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </a>
      <a href="add_news.php" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        <i class="fas fa-plus-circle"></i> Add News
      </a>
      <a href="manage_news.php" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        <i class="fas fa-newspaper"></i> Manage News
      </a>
      <a href="view_comments.php" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        <i class="fas fa-comments"></i> View Comments
      </a>
    </nav>
    <div class="p-4 border-t border-blue-700">
      <p class="text-sm">👤 <?php echo $_SESSION['admin_name']; ?></p>
      <a href="logout.php" class="mt-2 inline-block px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg text-sm">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">
      Welcome back, <?php echo $_SESSION['admin_name']; ?> 
    </h2>
    <p class="text-gray-600 mb-8">Here’s a quick overview of your portal.</p>

    <!-- Dashboard Cards -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      
      <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-700">Total News</h3>
          <i class="fas fa-newspaper text-blue-600 text-2xl"></i>
        </div>
        <p class="text-3xl font-bold text-gray-900 mt-4"><?php echo $newsCount; ?></p>
      </div>

      <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-700">Comments</h3>
          <i class="fas fa-comments text-green-600 text-2xl"></i>
        </div>
        <p class="text-3xl font-bold text-gray-900 mt-4"><?php echo $commentCount; ?></p>
      </div>

      <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-700">Admins</h3>
          <i class="fas fa-user-shield text-purple-600 text-2xl"></i>
        </div>
        <p class="text-3xl font-bold text-gray-900 mt-4"><?php echo $adminCount; ?></p>
      </div>

    </div>
  </main>

</body>
</html>

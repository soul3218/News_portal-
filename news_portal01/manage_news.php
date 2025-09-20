<?php
// manage_news.php
include("config.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Delete news if requested
if (isset($_GET['delete'])) {
    $news_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM news WHERE id=$news_id");
    header("Location: manage_news.php");
    exit();
}

// Fetch all news
$result = mysqli_query($conn, "SELECT * FROM news ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage News</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

  <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📰 Manage News</h2>

    <div class="overflow-x-auto">
      <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
        <thead>
          <tr class="bg-blue-600 text-white text-left">
            <th class="px-4 py-3">ID</th>
            <th class="px-4 py-3">Title</th>
            <th class="px-4 py-3">Origin</th>
            <th class="px-4 py-3">Category</th>
            <th class="px-4 py-3">Created</th>
            <th class="px-4 py-3">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 text-gray-600"><?php echo $row['id']; ?></td>
            <td class="px-4 py-3 font-medium text-gray-800"><?php echo htmlspecialchars($row['title']); ?></td>
            <td class="px-4 py-3 text-gray-600"><?php echo $row['origin']; ?></td>
            <td class="px-4 py-3 text-gray-600"><?php echo $row['category']; ?></td>
            <td class="px-4 py-3 text-gray-500 text-sm"><?php echo $row['created_at']; ?></td>
            <td class="px-4 py-3 flex space-x-2">
              <a href="edit_news.php?id=<?php echo $row['id']; ?>" 
                 class="px-3 py-1 rounded-lg bg-green-500 hover:bg-green-600 text-white text-sm font-medium">Edit</a>
              <a href="manage_news.php?delete=<?php echo $row['id']; ?>" 
                 onclick="return confirm('Delete this news?')"
                 class="px-3 py-1 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-medium">Delete</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <div class="mt-6">
      <a href="admin_dashboard.php" 
         class="text-blue-600 hover:underline text-sm font-medium">⬅ Back to Dashboard</a>
    </div>
  </div>

</body>
</html>

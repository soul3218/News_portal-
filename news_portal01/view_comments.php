<?php
// view_comments.php
include("config.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all comments with news title + user name
$sql = "SELECT c.id, c.content, c.created_at, 
               u.name AS user_name, 
               n.title AS news_title
        FROM comments c
        JOIN users u ON c.user_id = u.id
        JOIN news n ON c.news_id = n.id
        ORDER BY c.created_at DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Comments</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

  <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">💬 All Comments</h2>

    <?php if (mysqli_num_rows($result) > 0) { ?>
      <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
          <thead>
            <tr class="bg-blue-600 text-white text-left">
              <th class="px-4 py-3">ID</th>
              <th class="px-4 py-3">User</th>
              <th class="px-4 py-3">News</th>
              <th class="px-4 py-3">Comment</th>
              <th class="px-4 py-3">Date</th>
              <th class="px-4 py-3">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-600"><?php echo $row['id']; ?></td>
                <td class="px-4 py-3 font-medium text-gray-800"><?php echo htmlspecialchars($row['user_name']); ?></td>
                <td class="px-4 py-3 text-gray-600"><?php echo htmlspecialchars($row['news_title']); ?></td>
                <td class="px-4 py-3 text-gray-700"><?php echo htmlspecialchars($row['content']); ?></td>
                <td class="px-4 py-3 text-sm text-gray-500"><?php echo $row['created_at']; ?></td>
                <td class="px-4 py-3">
                  <a href="delete_comment.php?id=<?php echo $row['id']; ?>" 
                     onclick="return confirm('Delete this comment?')"
                     class="px-3 py-1 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-medium">
                     Delete
                  </a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php } else { ?>
      <p class="text-gray-600">No comments found.</p>
    <?php } ?>

    <div class="mt-6">
      <a href="admin_dashboard.php" 
         class="text-blue-600 hover:underline text-sm font-medium">⬅ Back to Dashboard</a>
    </div>
  </div>

</body>
</html>

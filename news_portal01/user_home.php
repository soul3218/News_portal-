<?php
// user_home.php
include("config.php");

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If user not logged in → redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Handle new comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $news_id = (int)$_POST['news_id'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    if (!empty($comment)) {
        $sql = "INSERT INTO comments (news_id, user_id, content) 
                VALUES ('$news_id', '$user_id', '$comment')";
        mysqli_query($conn, $sql);
    }
}

// --- Category filter ---
$selected_category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : "";

// Fetch all distinct categories for dropdown
$cat_sql = "SELECT DISTINCT category FROM news";
$cat_result = mysqli_query($conn, $cat_sql);

// Fetch news
if (!empty($selected_category)) {
    $sql = "SELECT * FROM news WHERE category='$selected_category' ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM news ORDER BY created_at DESC";
}
$news_result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Home - News Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Header -->
<header class="bg-blue-700 text-white p-4 flex justify-between items-center shadow">
    <h1 class="text-xl font-bold">📰 GlobalView</h1>
    <div class="flex items-center space-x-4">
        <span class="text-sm">Welcome, <b><?= htmlspecialchars($user_name) ?></b> 👋</span>
                <a href="contact.php" class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded text-sm font-medium">Contact Us</a>

        <a href="logout.php" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-sm font-medium">Logout</a>
        
    </div>
</header>

<div class="max-w-6xl mx-auto p-6">

    <!-- Filter -->
    <div class="bg-white p-4 rounded-lg shadow mb-6 flex justify-between items-center">
        <form method="GET" action="" class="flex items-center space-x-3">
            <label for="category" class="font-medium text-gray-700">Category:</label>
            <select name="category" id="category" onchange="this.form.submit()" 
                    class="p-2 border rounded-lg focus:ring focus:ring-blue-300">
                <option value="">All</option>
                <?php while ($cat = mysqli_fetch_assoc($cat_result)) { ?>
                    <option value="<?= htmlspecialchars($cat['category']) ?>" 
                        <?= ($selected_category == $cat['category']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category']) ?>
                    </option>
                <?php } ?>
            </select>
        </form>
    </div>

    <h2 class="text-2xl font-bold mb-6 border-b pb-2">
        Latest News <?= !empty($selected_category) ? " - " . htmlspecialchars($selected_category) : "" ?>
    </h2>

    <?php 
    if (mysqli_num_rows($news_result) > 0) {
        while ($news = mysqli_fetch_assoc($news_result)) { ?>
            <article class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-2xl font-semibold mb-2"><?= htmlspecialchars($news['title']); ?></h3>
                <div class="flex items-center space-x-3 text-sm text-gray-600 mb-3">
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded"><?= htmlspecialchars($news['category']); ?></span>
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded"><?= htmlspecialchars($news['origin']); ?></span>
                    <span>📅 <?= date("M d, Y H:i", strtotime($news['created_at'])) ?></span>
                </div>

                <?php if (!empty($news['image'])) { ?>
                    <img src="uploads/<?= htmlspecialchars($news['image']); ?>" alt="News Image" 
                         class="rounded-lg my-4 mx-auto">
                <?php } ?>

                <p class="text-gray-800 mb-4 leading-relaxed">
                    <?= nl2br(htmlspecialchars($news['description'])); ?>
                </p>

                <!-- Comments -->
                <div class="mt-4 border-t pt-4">
                    <h4 class="font-semibold mb-3">💬 Comments</h4>

                    <?php
                    $nid = $news['id'];
                    $csql = "SELECT c.content, u.name, c.created_at 
                            FROM comments c 
                            JOIN users u ON c.user_id = u.id 
                            WHERE c.news_id='$nid' ORDER BY c.created_at DESC";
                    $cresult = mysqli_query($conn, $csql);

                    if (mysqli_num_rows($cresult) > 0) {
                        while ($comment = mysqli_fetch_assoc($cresult)) {
                            echo "<div class='bg-gray-50 p-3 rounded-lg mb-2'>
                                    <b class='text-blue-600'>" . htmlspecialchars($comment['name']) . ":</b> 
                                    " . htmlspecialchars($comment['content']) . "
                                    <div class='text-xs text-gray-500 mt-1'>📅 " . $comment['created_at'] . "</div>
                                  </div>";
                        }
                    } else {
                        echo "<p class='text-gray-500 mb-2'>No comments yet.</p>";
                    }
                    ?>

                    <!-- Add Comment -->
                    <form method="POST" class="mt-3">
                        <input type="hidden" name="news_id" value="<?= $news['id']; ?>">
                        <textarea name="comment" placeholder="Write a comment..." required
                                  class="w-full p-2 border rounded-lg mb-2 focus:ring focus:ring-blue-300"></textarea>
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Post Comment
                        </button>
                    </form>
                </div>
            </article>
    <?php 
        } 
    } else {
        echo "<p class='text-gray-600'>No news found in this category.</p>";
    }
    ?>

</div>
</body>
</html>

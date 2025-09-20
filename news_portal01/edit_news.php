<?php
// edit_news.php
include("config.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

// Get news ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_news.php");
    exit();
}
$news_id = intval($_GET['id']);

// Fetch news
$sql = "SELECT * FROM news WHERE id=$news_id LIMIT 1";
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: manage_news.php");
    exit();
}
$news = mysqli_fetch_assoc($result);

// Update news
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $origin = $_POST['origin'];
    $category = $_POST['category'];

    // Keep old image unless new one uploaded
    $image = $news['image'];
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $image_name;
        }
    }

    $update_sql = "UPDATE news 
                   SET title='$title', description='$description', image='$image', origin='$origin', category='$category' 
                   WHERE id=$news_id";

    if (mysqli_query($conn, $update_sql)) {
        $message = "✅ News updated successfully!";
        $result = mysqli_query($conn, "SELECT * FROM news WHERE id=$news_id LIMIT 1");
        $news = mysqli_fetch_assoc($result);
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit News</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-2xl">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">✏️ Edit News</h2>

    <?php if ($message): ?>
        <div class="mb-4 p-3 rounded-lg <?php echo (strpos($message,'✅')!==false) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="text" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" 
               class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>

        <textarea name="description" rows="5" 
                  class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required><?php echo htmlspecialchars($news['description']); ?></textarea>

        <div>
            <label class="block font-semibold text-gray-700 mb-1">Origin:</label>
            <select name="origin" 
                    class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="Indian" <?php if ($news['origin']=="Indian") echo "selected"; ?>>Indian</option>
                <option value="Foreign" <?php if ($news['origin']=="Foreign") echo "selected"; ?>>Foreign</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1">Category:</label>
            <select name="category" 
                    class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="Politics" <?php if ($news['category']=="Politics") echo "selected"; ?>>Politics</option>
                <option value="Sports" <?php if ($news['category']=="Sports") echo "selected"; ?>>Sports</option>
                <option value="Tech" <?php if ($news['category']=="Tech") echo "selected"; ?>>Tech</option>
                <option value="Crime" <?php if ($news['category']=="Crime") echo "selected"; ?>>Crime</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1">Current Image:</label>
            <?php if (!empty($news['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($news['image']); ?>" alt="News Image" class="w-40 rounded-lg border mb-3">
            <?php endif; ?>
            <input type="file" name="image" class="block w-full text-sm text-gray-500">
        </div>

        <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200">
            Update News
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="manage_news.php" class="text-blue-600 hover:underline">⬅ Back to Manage News</a>
    </div>
</div>

</body>
</html>

<?php
// add_news.php
include("config.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $origin = $_POST['origin'];
    $category = $_POST['category'];

    // Handle image upload
    $image = "";
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

    // Insert into DB
    $sql = "INSERT INTO news (title, description, image, origin, category) 
            VALUES ('$title', '$description', '$image', '$origin', '$category')";

    if (mysqli_query($conn, $sql)) {
        $message = "✅ News added successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add News</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

  <div class="w-full max-w-2xl bg-white shadow-xl rounded-2xl p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📰 Add News</h2>

    <!-- Success/Error Message -->
    <?php if ($message): ?>
      <div class="mb-4 p-4 rounded-lg text-sm 
                  <?php echo (strpos($message, '✅') !== false) 
                      ? 'bg-green-100 text-green-700 border border-green-300' 
                      : 'bg-red-100 text-red-700 border border-red-300'; ?>">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <!-- Form -->
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
      
      <div>
        <label class="block text-sm font-medium text-gray-700">News Title</label>
        <input type="text" name="title" required
               class="w-full mt-1 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">News Description</label>
        <textarea name="description" rows="5" required
                  class="w-full mt-1 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Origin</label>
        <select name="origin" required
                class="w-full mt-1 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
          <option value="Indian">Indian</option>
          <option value="Foreign">Foreign</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Category</label>
        <select name="category" required
                class="w-full mt-1 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
          <option value="Politics">Politics</option>
          <option value="Sports">Sports</option>
          <option value="Tech">Tech</option>
          <option value="Crime">Crime</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Upload Image</label>
        <input type="file" name="image"
               class="w-full mt-1 px-3 py-2 border rounded-lg text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
      </div>

      <button type="submit" 
              class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
        ➕ Add News
      </button>
    </form>

    <!-- Back link -->
    <div class="mt-6 text-center">
      <a href="admin_dashboard.php" class="text-blue-600 hover:underline">⬅ Back to Dashboard</a>
    </div>
  </div>

</body>
</html>

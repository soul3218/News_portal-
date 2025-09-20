<?php
// contact.php
session_start();

// Handle form submission
$successMsg = $errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        // Receiver email
        $to = "your-email@example.com";  // TODO: Change to your email
        $subject = "Contact Form Message from $name";
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            $successMsg = "✅ Thank you, your message has been sent!";
        } else {
            $errorMsg = "❌ Failed to send message. Please try again.";
        }
    } else {
        $errorMsg = "⚠️ All fields are required!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - GlobalView</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Header -->
<header class="bg-blue-700 text-white p-4 flex justify-between items-center shadow">
    <h1 class="text-xl font-bold">📩 Contact Us</h1>
    <a href="user_home.php" 
       class="bg-gray-200 text-blue-700 px-4 py-2 rounded shadow hover:bg-gray-300 font-medium">
       ⬅ Back to User Home
    </a>
</header>

<div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg p-6 mt-8">
    <h2 class="text-2xl font-bold text-center mb-6">We’d love to hear from you!</h2>

    <?php if ($successMsg): ?>
        <p class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $successMsg ?></p>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <p class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $errorMsg ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-gray-700 font-medium mb-1">Your Name</label>
            <input type="text" name="name" required
                   class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>
        <div>
            <label class="block text-gray-700 font-medium mb-1">Your Email</label>
            <input type="email" name="email" required
                   class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>
        <div>
            <label class="block text-gray-700 font-medium mb-1">Message</label>
            <textarea name="message" rows="4" required
                      class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300"></textarea>
        </div>
        <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            Send Message
        </button>
    </form>
</div>

</body>
</html>

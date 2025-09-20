<?php
// index.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GlobalView - News Portal</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body 
  class="min-h-screen flex items-center justify-center bg-cover bg-center relative font-sans" 
  style="background-image: url('https://i.pinimg.com/1200x/32/41/3b/32413b63c2dbb53370fe0c974a5494e4.jpg');">

  <!-- Dark Overlay -->
  <div class="absolute inset-0 bg-black/60"></div>

  <!-- Card -->
  <div class="relative z-10 max-w-lg w-full bg-white/95 backdrop-blur-lg shadow-2xl rounded-2xl overflow-hidden border border-gray-300">

      <!-- Header -->
      <div class="bg-gradient-to-r from-red-600 to-red-700 text-white py-8 px-6 text-center">
          <h1 class="text-4xl font-extrabold tracking-wide drop-shadow-lg">Welcome to the GlobalView</h1>
          <p class="mt-2 text-base text-red-100 font-medium">Your daily source of trusted news</p>
      </div>

      <!-- Body -->
      <div class="p-10 text-center">
          <p class="text-gray-700 text-lg font-medium mb-8">Choose your role to continue</p>
          
          <a href="admin_login.php" 
             class="block w-full mb-5 py-3.5 text-lg font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition-all duration-300 shadow-lg hover:shadow-xl">
             👨‍💼 I am Admin
          </a>
          
          <a href="user_login.php" 
             class="block w-full py-3.5 text-lg font-semibold rounded-lg bg-green-600 hover:bg-green-700 text-white transition-all duration-300 shadow-lg hover:shadow-xl">
             👤 I am User
          </a>
      </div>

      <!-- Footer -->
      <div class="bg-gray-100 py-5 text-center text-sm text-gray-500 border-t">
          © <?php echo date("Y"); ?> GlobalView. All rights reserved.
      </div>
  </div>

</body>
</html>

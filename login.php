<?php 


session_start();

if(isset($_SESSION['user'])) {
 header('Location: home.php');
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css" />
    <title>Drone App</title>
  </head>
  <body>
    <div class="center">
        <div class="w-3/12 mx-auto drop-shadow-lg bg-white">
          <form method="post" id="form" name="form" enctype="multipart/form-data" class="form px-10 py-8">
            <h2 class="text-center mb-5">Sign In</h2>
            <div class="form-control mb-2 pb-6">
                <label class="text-gray-400 mb-1 block" for="Email">Email</label>
                <input type="text" id="Email" name="email" class="border-2 border-gray-3 w-full p-3 block rounded" placeholder="Enter Email" />
                <small>Error Message</small>
            </div>
            <div class="form-control mb-2 pb-6">
            <label class="text-gray-400 mb-1 block" for="Password">Password</label>
            <input type="password" id="Password" name="pass" class="border-2 border-gray-3 w-full p-3 block rounded" placeholder="Enter Password" />
            <small>Error Message</small>
            </div>
            <button class="bg-black border-2 border-black text-white block p-2 mt-4 w-full hover:bg-white hover:text-black transition-all rounded" name="submit" value="Send" type="submit">Submit</button>
          </form>
        </div>
    </div>
    <footer class="bg-black text-white py-3 px-12">
       &copy; <a href="index.php" class="font-semibold">Dron<span class='text-blue-600'>ey</span></a> <?php $year = date("Y"); echo $year; ?>, <span class="font-semibold">Created By Ayoub</span> 
   </footer>
    <script src="assets/js/login.js"></script>
  </body>
</html>
<?php 
 session_start();

 if(isset($_SESSION['user'])) {
  header('Location: home.php');
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- font awsome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <!-- Tailwind css -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- style -->
  <link rel="stylesheet" href="assets/css/style.css">
  <title>Drone App</title>
</head>
<body>
  <div class="bg-hero bg-blue-200 py-5 px-12 h-screen">
    <header class='flex items-center justify-between'>
      <a href="index.php" class="font-bold text-xl">Dron<span class='text-blue-600'>ey</span></a>
      <nav>
        <ul class='md:flex hidden items-center justify-between w-80'>
          <li><a href="#hero" class='font-semibold hover:text-blue-600'>Home</a></li>
          <li><a href="#feautures" class='font-semibold hover:text-blue-600'>Features</a></li>
          <li><a href="#about" class='font-semibold hover:text-blue-600'>About</a></li>
        </ul>
      </nav>
      <a href="login.php" class='hidden md:block bg-blue-700 text-white font-semibold py-2 px-7 rounded'>Log In</a>
      <!-- <nav class="mobile">
        <ul class='mobile-nav'>
          <i class="fa-solid cursor-pointer fa-xmark"></i>
          <li><a href="#hero">Home</a></li>
          <li><a href="#feautures">Features</a></li>
          <li><a href="#about">About</a></li>
          <li> <a href="login.php" class='block bg-blue-700 text-white font-semibold py-2 px-7 rounded'>Log In</a></li>
        </ul>
      </nav> -->
      <i id="burger" class="fa-solid fa-bars text-xl cursor-pointer md:hidden"></i>
    </header>
    <section id="hero" class="flex md:items-center md:justify-between pt-28 ">
        <div class="md:w-7/12 w-full">
          <div class="md:w-10/12 w-full">
            <h1 class='font-black text-4xl leading-12 mb-1'>Trash Resycle Company with Trash Detection Ability Using Drones</h1>
            <p class='font-thin mb-6 w-10/12'>if you are a worker please login and if you don't have an account contact your supervisor</p>
            <a href="login.php" class='border-2 border-blue-700 bg-transparent text-blue-700 hover:bg-blue-700 hover:text-white hover:ease-in transition duration-300 font-semibold py-2 px-7 rounded'>Log In</a>
          </div>
        </div>
        <div class="md:w-5/12 md:block hidden">
          <img src="assets/images/drone.jpg" class="w-full" alt="drone image">
        </div>
    </section>
  </div>
  <section class="px-12 mt-14 text-center md:text-left" id='feautures'>
    <h2 class='text-center text-2xl font-bold'>How Its Work</h2>
    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-4 mt-8">
      <div class="bg-gray-100 rounded mt-5 shadow md:shadow-lg md:mt-0 shadow-gray-500/50 p-4">
        <div class="bg-blue-300 w-10 py-1 text-center mx-auto md:mx-0 rounded ">
          <i class="fa-solid fa-gear"></i>
        </div>
        <h4 class='mt-4 text-2xl md:w-4/5'>Get the trash places</h4>
      </div>
      <div class="bg-gray-100 rounded mt-5 shadow md:shadow-lg md:mt-0 shadow-gray-500/50 p-4"> 
        <div class="bg-amber-300 w-10 py-1 text-center mx-auto md:mx-0 rounded ">
          <i class="fa-solid fa-database"></i>
        </div>
        <h4 class='mt-4 text-2xl md:w-4/5'>Analyzing Data with the closest worker</h4>
      </div>
      <div class="bg-gray-100 rounded mt-5 shadow md:shadow-lg md:mt-0 shadow-gray-500/50 p-4">
        <div class="bg-red-300 w-10 py-1 text-center mx-auto md:mx-0 rounded ">
          <i class="fa-solid fa-user"></i>
        </div>
        <h4 class='mt-4 text-2xl md:w-4/5'>Apply it to the workers</h4>
      </div>
      <div class="bg-gray-100 rounded mt-5 shadow md:shadow-lg md:mt-0 shadow-gray-500/50 p-4">
        <div class="bg-violet-300 w-10 py-1 text-center mx-auto md:mx-0 rounded ">
          <i class="fa-solid fa-hand-sparkles"></i>
        </div>
        <h4 class='mt-4 text-2xl md:w-4/5'>get the work done</h4>
      </div>
    </div>
  </section>
  <section class='px-12 md:mt-40 mt-20 flex items-center justify-between flex-col md:flex-row' id='about'>
    <div class="md:w-1/2 w-full">
      <div class="w-full">
          <img src="assets/images/drone.jpg" alt="drone">
      </div>
    </div>
    <div class="md:w-1/2 w-full mt-5 ml-8">
        <h2 class='text-4xl font-black'>About Us</h2>
        <p class='text-gray-400 my-6 leading-8'>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptate vero sed nihil, velit, ut eum consectetur non eligendi aspernatur veniam amet ab fugit quam necessitatibus itaque, asperiores iusto. Ea, quidem!</p>
        <a href="login.php" class='text-blue-500'>Log In</a>
    </div>
  </section>
  <footer class="md:mt-28 mt-10 bg-black text-white py-3 px-12">
     &copy; <a href="index.php" class="font-semibold">Dron<span class='text-blue-600'>ey</span></a> , <span class="font-semibold">Created By Ayoub</span> 
  </footer>
  <script src="assets/js/script.js"></script>
</body>
</html>
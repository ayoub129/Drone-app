<?php
require_once('config/config.php');

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

$data = json_decode($_SESSION['user']);

if ($data->is_admin == false) {
    header('Location: home.php');
}

if (isset($_POST['submit'])) {
    session_destroy();
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <!-- mapbox -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.13.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.13.0/mapbox-gl.js"></script>
    <!-- font awsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- Tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- custom css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Drone Trash Finder</title>
</head>

<body class="relative">
    <header class="bg-blue-200 py-5 px-4 text-black flex justify-between items-center font-semibold">
        <div class="logo-place">
            <a href="index.php" class="font-bold text-xl ml-1">Dron<span class='text-blue-600'>ey</span></a>
        </div>
        <nav class="w-1/5">
            <ul class="flex justify-between item-center w-full">
                <li class="hover:text-blue-600 transition-all duration-500"><a href="index.php">Dashboard</a></li>
                <li class=" hover:text-blue-600 transition-all duration-500"><a href="work.php">Work</a></li>
                <?php if ($data->is_admin == 'true') {  ?>
                    <li class="font-semibold text-blue-600 "><a href="users.php">Users</a></li>
                <?php } else {  ?>
                    <li class="hover:text-blue-600 transition-all duration-500"><a href="account.php">Account</a></li>
                <?php } ?>
            </ul>
        </nav>
        <div class="flex items-center w-40">
            <a href="account.php" class="h-9 w-9">
                <img src="<?php echo $data->image; ?>" class="w-full h-full rounded-full" alt="<?php echo $data->name; ?>">
            </a>
            <a href="account.php" class="account-name ml-3">
                <p class="text-sm text-blue-600 leading-4"><?php echo $data->name; ?> <br /> <span class="text-xs text-black tracking-wider"><?php echo $data->Job; ?></span> </p>
            </a>
            <form method="POST" class="ml-auto">
                <button name="submit" type="submit">
                    <i class="fa-solid fa-right-from-bracket text-black rotate-180 cursor-pointer"></i>
                </button>
            </form>
        </div>
    </header>

    <form method="post">
        <div class="form-container">
            <label for="FirstName">FirstName</label>
            <input id="FirstName" name='FirstName' type="text">
        </div>
        <div class="form-container">
            <label for="LastName">LastName</label>
            <input id="LastName" name='LastName' type="text">
        </div>
        <div class="form-container">
            <label for="Email">Email</label>
            <input id="Email" type="email" name='Email' type="text">
        </div>
        <div class="form-container">
            <label for="password">Default Password</label>
            <input id="password" type="password" name='password' type="text">
        </div>
        <div class="form-container">
            <label for="Job">Job</label>
            <input id="Job" name='Job' type="text">
        </div>
        <div class="form-container">
            <label for="Description">Description</label>
            <textarea id="Description" name='Description'></textarea>
        </div>
        <div class="form-container">
            <label for="Image">Image</label>
            <input id="Image" name='Image' type="file">
        </div>
    </form>
</body>

</html>
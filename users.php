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

$result_count = mysqli_query($db, "SELECT COUNT(*) As total_users FROM `users` WHERE `is_admin`= 'false' ");
$total_users = mysqli_fetch_array($result_count);
$total_users = $total_users['total_users'];


$sql = "SELECT * FROM `users`  WHERE `is_admin`= 'false' ";
$result = $db->query($sql);

if (isset($_POST['delet_user'])) {
    $id = $_POST['user_id'];
    $deletUser = "DELETE FROM `users` WHERE `id`='$id'";
    $deleted = $db->query($deletUser);
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

    <div class="mt-4 px-4 w-full">
        <h2 class="text-4xl font-bold">Workers</h2>
        <p class="text-gray-400">you have <span class="font-black text-black"><?php echo $total_users ?> active workers</span> </p>
        <div class="flex align-center justify-between mt-5 relative">
            <input type="text" class="w-6/12 p-3 pl-9 border-2 border-gray-3 block rounded focus:none outline-none" placeholder="Search for workers ...">
            <i class="fas fa-search absolute text-gray-400 top-4 left-3"></i>
            <a href="addUser.php">
                <button class="bg-black border-2 border-black text-white block px-2 py-1 hover:bg-white hover:text-black transition-all rounded">Add User</button>
            </a>
        </div>

        <div class="bg-white w-full rounded grid grid-cols-4 gap-4 mt-8 p-4">
            <?php
            if ($result != '' and $result->num_rows > 0) :
                // output data of each row
                while ($row = $result->fetch_assoc()) :
            ?>
                    <div class="bg-gray-100 rounded p-4 text-center relative">
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?php echo $row['id'] ?>">
                            <button type="submit" name="delet_user">
                                <i class="fa-solid fa-trash absolute -top-2 -right-1 text-red-500"></i>
                            </button>
                        </form>
                        <div class="h-40 w-40 mx-auto mb-2">
                            <img src="<?php echo $row['image'] ?>" class="w-full h-full rounded-full" alt="<?php echo $row['name'] ?>">
                        </div>
                        <a href="account.php?id=<?php echo $row['id'] ?>" class="text-xl font-bold "><?php echo $row['name'] ?></a>
                        <p class="mt-1 font-light"><?php echo $row['Job'] ?></p>
                    </div>
            <?php
                endwhile;
            endif;
            ?>
        </div>
    </div>

</body>

</html>
<?php
require_once('config/config.php');

// Login Logout
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}
$data = json_decode($_SESSION['user']);

if (isset($_POST['submit'])) {
    session_destroy();
    header('Location: index.php');
}


if (isset($_POST['status_to_done'])) {
    $trash_id = $_POST['status_update_id'];
    $query2 = "UPDATE `trash` SET `status`='Done' WHERE `id`='$trash_id'";
    $result2 = $db->query($query2);
}

// pagination
if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}

$total_records_per_page = 3;
$offset = ($page_no - 1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";


$getTotalnoPage = function ($where) use ($db, $total_records_per_page) {
    $result_count = mysqli_query($db, "SELECT COUNT(*) As total_records FROM `trash` WHERE $where");
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    return $total_no_of_pages;
};

if ($data->is_admin == 'true') {
    $total_no_of_pages = $getTotalnoPage("`status`='Done' OR `status`='Waiting for approve'");
    $sql = "SELECT * FROM `trash` WHERE `status`='Done'  OR `status`='Waiting for approve' ORDER BY `id` DESC LIMIT $offset, $total_records_per_page";
    $result = $db->query($sql);
} else {
    $total_no_of_pages = $getTotalnoPage("`user_id`= '$data->id'");
    $sql = "SELECT * FROM `trash` WHERE `user_id` = '$data->id' LIMIT $offset, $total_records_per_page";
    $result = $db->query($sql);
}

$second_last = $total_no_of_pages - 1;


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
    <!-- lightbox -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.css" integrity="sha512-Woz+DqWYJ51bpVk5Fv0yES/edIMXjj3Ynda+KWTIkGoynAMHrqTcDUQltbipuiaD5ymEo9520lyoVOo9jCQOCA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- custom css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Drone Trash Finder</title>
</head>

<body class="bg-slate-100 ">
    <header class="bg-blue-200 py-5 px-4 text-black flex justify-between items-center font-semibold">
        <div class="logo-place">
            <a href="index.php" class="font-bold text-xl ml-1">Dron<span class='text-blue-600'>ey</span></a>
        </div>
        <nav class="w-1/5">
            <ul class="flex justify-between item-center w-full">
                <li class="hover:text-blue-600 transition-all duration-500 "><a href="index.php">Dashboard</a></li>
                <li class="text-blue-600 transition-all duration-500"><a href="work.php">Work</a></li>
                <?php if ($data->is_admin == 'true') {  ?>
                    <li class="hover:text-blue-600 transition-all duration-500"><a href="users.php">Users</a></li>
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
    <div class="mt-5 mx-4 h-90vh">
        <div class="work-table w-full pr-8">
            <h2 class="text-3xl py-4 font-black pr-7">
                <?php

                if ($data->is_admin == 'true') {
                    echo "Work Done";
                } else {
                    echo "Your Work";
                } ?>
            </h2>
            <table class="table-auto border-collapse border border-slate-400 w-full">
                <thead>
                    <tr class="bg-slate-200 text-left ">
                        <?php if ($data->is_admin == 'true') { ?>
                            <th class="border border-slate-300 p-2 capitalize">Image Uploaded</th>
                            <th class="border border-slate-300 p-2 capitalize">User</th>
                        <?php } ?>
                        <th class="border border-slate-300 p-2">Name</th>
                        <th class="border border-slate-300 p-2">Date Created</th>
                        <th class="border border-slate-300 p-2">Status</th>
                        <?php if ($data->is_admin == 'true') { ?>
                            <th class="border border-slate-300 p-2">Approve</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            $id = $row['user_id'];
                            $select_user = "SELECT * FROM `users` WHERE `id` = '$id' ";
                            $result_user = $db->query($select_user);
                    ?>
                            <tr class="text-left">
                                <?php if ($data->is_admin == 'true') { ?>
                                    <th class="border border-slate-300 p-2 capitalize w-28 h-28"><a href="assets/images/<?php echo $row['image'] ?>" data-lightbox="<?php echo $row['id'] ?>" data-title="<?php echo $row['trash'] ?>"> <img src="assets/images/<?php echo $row['image'] ?>" class="w-full h-full rounded-full" alt="<?php echo $row['trash'] ?>"> </th>
                                    <?php while ($user = $result_user->fetch_assoc()) { ?>
                                        <th class="border border-slate-300 p-2 capitalize"><?php echo $user['name'] ?></th>
                                <?php }
                                } ?>
                                <th class="border border-slate-300 p-2 capitalize"><?php echo $row['trash'] ?></th>
                                <th class="border border-slate-300 p-2"><?php echo $row['date'] ?></th>
                                <th class="border border-slate-300 p-2 <?php if ($row['status'] == 'Done') { ?> text-green-600 <?php } else { ?> text-yellow-500 <?php } ?>"><?php echo $row['status'] ?></th>
                                <?php if ($data->is_admin == 'true') { ?>
                                    <th class="border border-slate-300 p-2"><?php if ($row['status'] == 'Done') { ?><button class="bg-gray-200 p-2 rounded cursor-not-allowed">approved</button> <?php } else { ?> <form method="POST">
                                                <input type="hidden" name="status_update_id" value="<?php echo $row['id'] ?>">
                                                <button type="submit" name="status_to_done" class="bg-green-300 p-2 rounded">approve</button>
                                            </form> <?php } ?></th>
                                <?php } ?>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
            <div class="py-1 px-1 ">
                <strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
            </div>
            <table class="table-auto border-collapse border border-slate-400 mt-5 w-fit mx-auto">
                <thead>
                    <tr class="bg-slate-200 text-left ">
                        <th class="border border-slate-300 p-2 <?php if ($page_no <= 1) {
                                                                    echo "cursor-not-allowed ";
                                                                } ?>"><a <?php if ($page_no > 1) {
                                                                                echo "href='?page_no=$previous_page'";
                                                                            } ?>>Previous</a></th>
                        <?php
                        if ($total_no_of_pages <= 10) {
                            for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                                if ($counter == $page_no) {
                                    echo "<th class='border bg-blue-500 border-blue-500 text-white p-2 px-4'><a> $counter</a></th>";
                                } else {
                                    echo "<th class='border border-slate-300 p-2 px-4'><a href='?page_no=$counter'>$counter</a></th>";
                                }
                            }
                        } elseif ($total_no_of_pages > 10) {

                            if ($page_no <= 4) {
                                for ($counter = 1; $counter < 8; $counter++) {
                                    if ($counter == $page_no) {
                                        echo "<th class='border bg-blue-500 border-blue-500 p-2 px-4'><a>$counter</a></th>";
                                    } else {
                                        echo "<th class='border border-slate-300 p-2 px-4'><a href='?page_no=$counter'>$counter</a></th>";
                                    }
                                }
                                echo "<th><a>...</a></th>";
                                echo "<th><a href='?page_no=$second_last'>$second_last</a></th>";
                                echo "<th><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></th>";
                            } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                                echo "<th><a href='?page_no=1'>1</a></th>";
                                echo "<th><a href='?page_no=2'>2</a></th>";
                                echo "<th><a>...</a></th>";
                                for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                                    if ($counter == $page_no) {
                                        echo "<th class='border bg-blue-500 border-blue-500 p-2 px-4'><a>$counter</a></th>";
                                    } else {
                                        echo "<th class='border border-slate-300 p-2 px-4'><a href='?page_no=$counter'>$counter</a></th>";
                                    }
                                }
                                echo "<th><a>...</a></th>";
                                echo "<th><a href='?page_no=$second_last'>$second_last</a></th>";
                                echo "<th><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></th>";
                            } else {
                                echo "<th><a href='?page_no=1'>1</a></th>";
                                echo "<th><a href='?page_no=2'>2</a></th>";
                                echo "<th><a>...</a></th>";

                                for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                    if ($counter == $page_no) {
                                        echo "<th class='border bg-blue-500 border-blue-500 p-2 px-4'><a>$counter</a></th>";
                                    } else {
                                        echo "<th class='border border-slate-300 p-2 px-4'><a href='?page_no=$counter'>$counter</a></th>";
                                    }
                                }
                            }
                        }
                        ?>
                        <th class="border border-slate-300 p-2  <?php if ($page_no >= $total_no_of_pages) {
                                                                    echo "cursor-not-allowed";
                                                                } ?>">
                            <a <?php if ($page_no < $total_no_of_pages) {
                                    echo "href='?page_no=$next_page'";
                                } ?>>Next</a>
                        </th>
                        <?php if ($page_no < $total_no_of_pages) {
                            echo "<th class='border border-slate-300 p-2'><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></th>";
                        } ?>
                    </tr>
                </thead>
            </table>
        </div>
        <?php
        if ($data->is_admin != 'true') {

        ?>
            <div class="upload-table my-4 mt-16 h-fit bg-white rounded p-2 basis-1/4">
                <h2 class="text-3xl py-4 font-black pr-7">Your Uploads</h2>
                <?php

                if ($data->is_admin == 'true') {
                    $sql = "SELECT * FROM `trash` WHERE `status`='Done' ORDER BY `id` DESC LIMIT 6";
                    $result1 = $db->query($sql);
                } else {
                    $sql = "SELECT * FROM `trash` WHERE `user_id` = '$data->id' ORDER BY `id` DESC LIMIT 6";
                    $result1 = $db->query($sql);
                }



                if ($result1->num_rows > 0) {
                    // output data of each row
                    while ($row2 = $result1->fetch_assoc()) {
                ?>
                        <div class="flex my-5">
                            <img src="uploads/<?php echo $row2['image'] ?>" class="w-10 h-10 rounded-full" alt="<?php echo $row2['trash'] ?>">
                            <div class="ml-5">
                                <h5 class="mb-0"><?php echo $row2['trash'] ?></h5>
                                <span class="text-xs text-gray-400"><?php echo $row2['date'] ?></span>
                            </div>
                        </div>

                <?php }
                } ?>
            </div>
        <?php } ?>
    </div>



    <footer class="md:mt-auto mt-10 bg-blue-200 text-black py-3 px-4">
        &copy; <a href="index.php" class="font-semibold">Dron<span class='text-blue-600'>ey</span></a> , <span class="font-semibold">Created By Ayoub</span>
    </footer>
    <!-- Lightbox Script tag   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.js" integrity="sha512-0rYcJjaqTGk43zviBim8AEjb8cjUKxwxCqo28py38JFKKBd35yPfNWmwoBLTYORC9j/COqldDc9/d1B7dhRYmg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>
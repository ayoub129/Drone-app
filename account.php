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


$result_count = mysqli_query($db, "SELECT COUNT(*) As total_records FROM `trash` WHERE `user_id`= '$data->id' AND `status` = 'Done'");
$total_records = mysqli_fetch_array($result_count);
$total_records = $total_records['total_records'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account </title>
    <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
    <!-- Tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
    <!-- font awsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
</head>

<body>
    <header class="bg-blue-200 py-5 px-4 text-black flex justify-between items-center font-semibold">
        <div class="logo-place">
            <a href="index.php" class="font-bold text-xl ml-1">Dron<span class='text-blue-600'>ey</span></a>
        </div>
        <nav class="w-1/5">
            <ul class="flex justify-between item-center w-full">
                <li class="hover:text-blue-600 transition-all duration-500 "><a href="index.php">Dashboard</a></li>
                <li class="hover:text-blue-600 transition-all duration-500"><a href="work.php">Work</a></li>
                <?php if ($data->is_admin == 'true') {  ?>
                    <li class="hover:text-blue-600 transition-all duration-500"><a href="users.php">Users</a></li>
                <?php } else {  ?>
                    <li class="text-blue-600 transition-all duration-500"><a href="account.php">Account</a></li>
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
    <main class="profile-page">
        <section class="relative block h-500-px">
            <div class="absolute top-0 w-full h-full bg-center bg-cover" style="
            background-image: url('https://images.unsplash.com/photo-1499336315816-097655dcfbda?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=2710&amp;q=80');
          ">
                <span id="blackOverlay" class="w-full h-full absolute opacity-50 bg-black"></span>
            </div>
            <div class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden h-70-px" style="transform: translateZ(0px)">
                <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
                    <polygon class="text-blueGray-200 fill-current" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </section>
        <section class="relative py-16 bg-blueGray-200">
            <div class="container mx-auto px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg -mt-64">
                    <div class="px-6">
                        <div class="flex flex-wrap justify-center">
                            <div class="w-full lg:w-3/12 px-4 lg:order-2 flex justify-center">
                                <div class="relative">
                                    <img alt="<?php echo $data->name; ?>" src="<?php echo $data->image; ?>" class="shadow-xl rounded-full h-32 w-32 align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-xs">
                                </div>
                            </div>
                            <div class="w-full lg:w-4/12 px-4 lg:order-3 lg:text-right lg:self-center">
                                <div class="py-6 px-3 mt-32 sm:mt-0">
                                    <button class="bg-pink-500 active:bg-pink-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" type="button">
                                        <?php echo $data->status; ?>
                                    </button>
                                </div>
                            </div>
                            <div class="w-full lg:w-4/12 px-4 lg:order-1">
                                <div class="flex justify-center py-4 lg:pt-4 pt-8">
                                    <div class="lg:mr-4 p-3 text-center">
                                        <span class="text-xl font-bold block uppercase tracking-wide text-blueGray-600"><?php echo $total_records ?></span><span class="text-sm text-blueGray-400">Work Done</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-12">
                            <h3 class="text-4xl font-semibold leading-normal mb-2 text-blueGray-700 mb-2">
                                <?php echo $data->name; ?>
                            </h3>
                            <div class="text-sm leading-normal mt-0 mb-2 text-blueGray-400 font-bold uppercase">
                                <i class="fas fa-map-marker-alt mr-2 text-lg text-blueGray-400"></i>
                                <?php echo $data->address; ?>
                            </div>
                            <div class="mb-2 text-sm text-blueGray-400 leading-normal mt-4 font-bold uppercase">
                                <i class="fas fa-briefcase mr-2 text-lg text-blueGray-400 "></i> <?php echo $data->Job; ?>
                            </div>
                        </div>
                        <div class="mt-10 py-10 border-t border-blueGray-200 text-center">
                            <div class="flex flex-wrap justify-center">
                                <div class="w-full lg:w-9/12 px-4">
                                    <p class="mb-4 text-lg leading-relaxed text-blueGray-700">
                                        <?php echo $data->about; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="relative bg-blueGray-200 pt-8 pb-6 mt-8">
                <div class="container mx-auto px-4">
                    <div class="flex flex-wrap items-center md:justify-between justify-center">
                        <div class="w-full md:w-6/12 px-4 mx-auto text-center">
                            <div class="text-sm text-blueGray-500 font-semibold py-1">
                                &copy; <a href="index.php" class="font-semibold">Dron<span class='text-blue-600'>ey</span></a> , <span class="font-semibold">Created By Ayoub</span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </section>
    </main>
</body>

</html>
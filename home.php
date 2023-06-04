<?php
require_once('config/config.php');

session_start();
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
}
$data = json_decode($_SESSION['user']);

if (isset($_POST['submit'])) {
  session_destroy();
  header('Location: index.php');
}

if ($data->is_admin == 'true') {
  $sql = "SELECT * FROM `trash` WHERE `status`='urgent' ORDER BY `id` DESC LIMIT 3";
  $result = $db->query($sql);
} else {
  $sql = "SELECT * FROM `trash` WHERE `user_id` = '$data->id' AND `status` = 'working on it'";
  $result = $db->query($sql);
}



/* 
  upload files functionalities
*/

// Check if image file is a actual image or fake image
if (isset($_POST["upload"])) {

  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["fileupload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $errors;
  $check = getimagesize($_FILES["fileupload"]["tmp_name"]);
  if ($check !== false) {
    $uploadOk = 1;
  } else {
    $errors = "File is not an image.";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
  ) {
    $errors =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    $errors =  "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file)) {
      $sqlupload = mysqli_query($db, "SELECT id FROM `trash` WHERE `user_id` = '$data->id' AND `status` = 'working on it' LIMIT 1");
      $rowupload = mysqli_fetch_assoc($sqlupload);
      $id = $rowupload['id'];
      $image = htmlspecialchars(basename($_FILES["fileupload"]["name"]));
      $query = "UPDATE `trash` SET `image`='$image' , `status`='Waiting for approve' WHERE `id`='$id'";
      $result = $db->query($query);

      $query2 = "UPDATE `users` SET `status`='free' WHERE `id`='$data->id'";
      $result2 = $db->query($query2);
    } else {
      $errors = "Sorry, there was an error uploading your file.";
    }
  }
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
        <li class="font-semibold text-blue-600 "><a href="index.php">Dashboard</a></li>
        <li class=" hover:text-blue-600 transition-all duration-500"><a href="work.php">Work</a></li>
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

  <input type="hidden" id="adminuser" value="<?php if ($data->is_admin == 'true') { ?> admin <?php } else { ?> user <?php } ?> ">
  <div class="trash-info">
    <div class="flex items-center justify-between">
      <h2 class="text-3xl pt-4 pl-2 font-black pr-7">Work Places</h2>
      <a href="work.php" class="mt-4 mr-2 bg-gray-800 p-1 rounded text-white hover:bg-white hover:text-black border-2 border-gray-800 transition-all duration-500">All work</a>
    </div>
    <p class='pl-3 pr-7'>places that currently full of trash</p>
    <?php
    if ($result != '' and $result->num_rows > 0) {
      // output data of each row
      while ($row = $result->fetch_assoc()) {
    ?>
        <div class="py-4 mt-4 pl-3 bg-blue-100 pr-3">
          <div class="flex items-center justify-between">
            <h4 class='text-2xl  cursor-pointer nameclick'><?php echo $row['trash'] ?></h4>
            <span class='<?php if ($row['status'] == 'urgent') {  ?> text-white bg-red-500 <?php } else { ?> text-white bg-yellow-500 <?php } ?> font-thin border-solid border-2 p-1 px-2 rounded '><?php echo $row['status'] ?></span>
            <input type="hidden" class="lon" value="<?php echo $row['lon'] ?>">
            <input type="hidden" class="lat" value="<?php echo $row['lat'] ?>">
            <input type="hidden" id="id" value="<?php echo $row['id'] ?>">
          </div>
          <p class='font-black'><?php echo $row['date'] ?> / <?php echo $row['time'] ?></p>
        </div>
    <?php
      }
    } else {
      if ($data->is_admin == 'true') {
        echo "<hr>";
        echo "<p class='py-4 pl-2'> There is No Trash For The Moment </p>";
      } else {
        echo "<hr>";
        echo "<p class='py-4 pl-2'> You are currently free wait until you got a new work </p>";
      }
    }
    ?>
  </div>
  <?php if ($data->is_admin == 'true') { ?>
    <div class="users-info">
      <div class="flex items-center justify-between">
        <h2 class="text-3xl pt-4 pl-2 font-black pr-7">Users</h2>
        <a href="users.php" class="mt-4 mr-2 bg-gray-800 p-1 rounded text-white">All Users</a>
      </div>
      <?php
      $sql1 = "SELECT * FROM `users` WHERE `is_admin` = 'false' AND `status` = 'free' LIMIT 5";
      $result1 = $db->query($sql1);
      if ($result1->num_rows > 0) {
        // output data of each row
        while ($row = $result1->fetch_assoc()) { ?>
          <div class="py-4 mt-4 pl-3 bg-blue-100 pr-3">
            <div class="flex items-center">
              <div class="h-9 w-9">
                <img src="<?php echo $row['image'] ?>" class="w-full h-full rounded-full" alt="<?php echo $row['name'] ?>">
              </div>
              <div class="name-job ml-4">
                <h4 class='text-xl cursor-pointer mb-0'><?php echo $row['name'] ?></h4>
                <p class='font-black'><?php echo $row['Job'] ?></p>
                <input type="hidden" class="lon" value="<?php echo $row['Lon'] ?>">
                <input type="hidden" class="lat" value="<?php echo $row['Lat'] ?>">
              </div>
              <p class='<?php if ($row['status'] == 'free') {  ?> text-green-500 border-green-500 <?php } else { ?> text-red-500 border-red-500 <?php } ?> font-thin border-solid border-2 p-1 rounded ml-auto'><?php echo $row['status'] ?></p>
            </div>
          </div>
      <?php
        }
      } else {
        echo "There is No Free Users";
      }
      ?>
    </div>
  <?php } ?>

  <div class="markers-info">
    <h2 class="text-3xl pt-4 pl-2 font-black pr-7">Map Info</h2>
    <div class="flex items-center mt-3">
      <i class="fa-solid fa-location-dot fa-2x pl-2 pt-2"></i>
      <p class="ml-4 mt-2">Your Current Location</p>
    </div>
    <div class="flex items-center mt-3">
      <i class="fa-solid fa-location-dot text-yellow-600 fa-2x pl-2 pt-2"></i>
      <p class="ml-4 mt-2">Trash Places</p>
    </div>
    <?php if ($data->is_admin == 'true') { ?>
      <div class="flex items-center mt-3">
        <i class="fa-solid fa-location-dot text-green-500 fa-2x pl-2 pt-2"></i>
        <p class="ml-4 mt-2">Workers places</p>
      </div>
    <?php } ?>
  </div>
  <?php if ($data->is_admin == 'true') { ?>
    <div class="custom-model-main">
      <div class="custom-model-inner">
        <div class="close-btn close">×</div>
        <div class="custom-model-wrap">
          <h5 class="text-xl font-bold">sign it to a worker</h5>
          <hr>
          <?php
          $sql2 = "SELECT * FROM `users` WHERE `is_admin` = 'false' AND `status` = 'free' LIMIT 5";
          $result2 = $db->query($sql2);
          if ($result2->num_rows > 0) {
            // output data of each row
            while ($row = $result2->fetch_assoc()) { ?>
              <div class="pop-up-content-wrap popup-users my-3 hover:text-blue-500 cursor-pointer">
                <p><?php echo $row['name'] ?></p>
                <input type="hidden" value="<?php echo $row['id'] ?>">
              </div>
            <?php }
          } else { ?>
            <div class="pop-up-content-wrap">
              There is No User Free
            </div>
          <?php } ?>
        </div>
      </div>
      <div class="bg-overlay close"></div>
    </div>
  <?php } else { ?>
    <!-- // done -->
    <div class="custom-model-main">
      <div class="custom-model-inner">
        <div class="close-btn close">×</div>
        <div class="custom-model-wrap">
          <h5 class="text-xl font-bold">You Finished The Work</h5>
          <hr>
          <div class="pop-up-content-wrap">
            <form action="" method="post" enctype="multipart/form-data" id="uploadForm">
              <div class="input-container">
                <p class="input-p">ADD IMAGE</p>
                <label for="fileupload" class="custom-file-upload">
                  <i class="fa-solid fa-cloud-arrow-up"></i>
                  Add image or <br>
                  <p>Drag and drop files here</p>
                </label>
                <input id="fileupload" name="fileupload" multiple type="file" />
              </div>
              <p class="capitalize text-xs"> <span class="text-red-600">*</span> only JPG, JPEG, PNG & GIF files are allowed</p>
              <button type="submit" name="upload" class="bg-blue-200 p-2 rounded text-black">Submit</button>
            </form>
          </div>
        </div>
      </div>
      <div class="bg-overlay close"></div>
    </div>
  <?php } ?>

  <div id="map"></div>
  <footer class="mt-90vh bg-blue-200 text-black py-3 px-4">
    &copy; <a href="index.php" class="font-semibold">Dron<span class='text-blue-600'>ey</span></a> , <span class="font-semibold">All Right Reserved</span>
  </footer>
  <script src="assets/js/script.js"></script>
</body>

</html>
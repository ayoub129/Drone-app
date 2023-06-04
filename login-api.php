<?php 

function login() { 
    require 'config/config.php'; 
    $json = json_decode(file_get_contents('php://input'), true); 
    if(isset($json)) {
      $password = $json['password']; 
      $email = $json['Email'];
      $data ='';
      $query = "SELECT * FROM users WHERE `email`='$email' AND `password`='$password'"; 
       $result= $db->query($query);
       $rowCount=$result->num_rows;
       
       if($rowCount>0){
         $data = $result->fetch_object();
         $data = json_encode($data);
         session_start();
         $_SESSION["user"] = $data;
           echo  '{"data":'.$data.'}';
       } else {
           echo '{"data": "no user"}';
       }
     }
         
 }
 
 // call the login function
 login(); 
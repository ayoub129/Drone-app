<?php 

function upadteStatus() { 
    require 'config/config.php'; 
    $json = json_decode(file_get_contents('php://input'), true); 
    if(isset($json)) {
      $state = 'working on it'; 
      $userId = $json['userId'];
      $trash_id = $json['trashId'];
      $query = "UPDATE `trash` SET `user_id`='$userId' , `status`='$state' WHERE `id`='$trash_id'"; 
      $result= $db->query($query);
      if($result) {
        echo  '{"data": "No Errooooors"}';
      } else {
        echo  '{"data": "error happened"}';
      }
     }    
 }


 function upadteUserStatus() { 
    require 'config/config.php'; 
    $json = json_decode(file_get_contents('php://input'), true); 
    if(isset($json)) {
      $status = $json['userStatus']; 
      $userId = $json['userId'];
      $query1 = "UPDATE `users` SET `status`='$status' WHERE `id`='$userId'"; 
      $result1= $db->query($query1);
     }    
 }
 
 // call the upadteStatus function
 upadteUserStatus();
 upadteStatus(); 
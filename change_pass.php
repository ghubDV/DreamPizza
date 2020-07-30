<?php
include 'dbconn.php';

session_start();
$user_email=$_SESSION['email'];
$user_pass=$_POST['pwd'];
$check_pass=$_POST['re_pwd'];

if($user_pass==$check_pass && strlen($user_pass)>=4){
  $hash=password_hash($user_pass, PASSWORD_DEFAULT);
  $query="UPDATE users SET password = ? WHERE email = ?";
  $sql_sec=$db_connect->prepare($query);
  $sql_sec->bind_param("ss", $hash, $user_email);
  $sql_sec->execute();
  $success = 'login?result=changed';
  $_SESSION['current_page'] = 'change_pass';
  header('location: '.$success);
  die();
}

else if($user_pass==$check_pass && strlen($user_pass)<4){
  $_SESSION['current_page'] = 'change_pass';
  header('location:'.'change?result=short');
  die();
}

else{
  $fail = 'change?result=fail';
  $_SESSION['current_page'] = 'change_pass';
  header('location: '.$fail);
  die();
}

?>

<?php
include 'dbconn.php';
//check if the user just entered the address of log.php without going through login.php -> redirect to login.php

if(empty($_POST)){
  header('Location:login?_rdr');
  die();
}

session_start();

//get password hash from db

$login_email=$_POST['email'];
$login_pass=$_POST['pwd'];

//get password hash

$query_hash="SELECT password FROM users WHERE email = ?";
$sql_sec=$db_connect->prepare($query_hash);
$sql_sec->bind_param("s", $login_email);
$sql_sec->execute();
$result=$sql_sec->get_result();
$hash=$result->fetch_array(MYSQLI_ASSOC);
$str_hash=$hash['password'];

//query to get check if account is activated or not

$query_if_activated= "SELECT email_activation FROM users WHERE email = ?";
$sql_sec=$db_connect->prepare($query_if_activated);
$sql_sec->bind_param("s", $login_email);
$sql_sec->execute();
$result=$sql_sec->get_result();
$fetch=$result->fetch_array(MYSQLI_ASSOC);
$is_activated = $fetch['email_activation'];

//verify login credentials

$query="SELECT email FROM users WHERE email = ?";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("s", $login_email);
$sql_sec->execute();
$result=$sql_sec->get_result();
$fetch=$result->fetch_array(MYSQLI_ASSOC);

//log in the user

if(mysqli_num_rows($result) && password_verify($login_pass, $str_hash) && $is_activated){
  $get_email=$fetch['email'];
  $query="SELECT * FROM users WHERE email = ?";
  $sql_sec=$db_connect->prepare($query);
  $sql_sec->bind_param("s", $login_email);
  $sql_sec->execute();
  $result=$sql_sec->get_result();
  $get=$result->fetch_array(MYSQLI_ASSOC);
  $fname=$get['f_name'];

  //login session variables

  $_SESSION['address']=$get['address'];
  $_SESSION['twitter']=$get['twitter_name'];
  $_SESSION['l_name']=$get['l_name'];
  $_SESSION['country']=$get['country'];
  $_SESSION['current_page']='log';
  $_SESSION['username']=$fname;
  $_SESSION['email']=$get_email;
  $_SESSION['tab_index']=1;

  if(!empty($_SESSION['twitter'])){
    $_SESSION['get_tweet']=true;
  }


  $home='index';
  header('location: '.$home);
  die();
}

//email or password incorrect

else if(!mysqli_num_rows($result) || !password_verify($login_pass, $str_hash)){
  $fail='login?result=fail';
  $_SESSION['current_page']='log';
  header('location: '.$fail);
  die();
}

//user account is not activated

else if(mysqli_num_rows($result) && password_verify($login_pass, $str_hash) && !$is_activated){
  $fail='login?result=not_active';
  $_SESSION['current_page']='log';
  header('location: '.$fail);
  die();
}

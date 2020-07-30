<?php
include 'dbconn.php';
session_start();

if(isset($_SESSION['current_page'])){
  $prev_page=$_SESSION['current_page'];
  $_SESSION['current_page'] = pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME);
}
else{
  $prev_page='';
}

if(empty($_GET)){
  header('location:index?_rdr');
  die();
}

if (isset($_GET["code"])){
    $activation_code=$_GET["code"];
}

session_start();

//check if the given verification code matches the account
$query= "SELECT verification_code FROM users WHERE verification_code = ? AND email_activation = ?";
$is_activated='0';
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("ss",$activation_code, $is_activated);
$sql_sec->execute();
$select_db_vcode=$sql_sec->get_result();

//getting the account email

$query="SELECT email FROM users WHERE verification_code = ?";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("s",$activation_code);
$sql_sec->execute();
$result = $sql_sec->get_result();
$fetch = $result->fetch_array(MYSQLI_ASSOC);
$user_email = $fetch['email'];

if(mysqli_num_rows($select_db_vcode)){

  //set email activation

  $length = 32;
  $verification_code = substr(str_shuffle(md5(time())),0,$length);

  $query= "UPDATE users SET email_activation = ? , verification_code = ? WHERE email = ?";
  $is_activated='1';
  $sql_sec=$db_connect->prepare($query);
  $sql_sec->bind_param("sss",$is_activated ,$verification_code, $user_email);
  $sql_sec->execute();

  $success="login?result=activated";
  $sql_sec->close();
  $db_connect->close();
  $_SESSION['current_page']='activate';
  header("location: ".$success);
  die();
}
else{
  $db_connect->close();
  $fail="login?result=not_activated";
  $_SESSION['current_page']='activate';
  header("location: ".$fail);
  die();
}
?>

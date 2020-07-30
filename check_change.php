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

if(isset($_GET['code'])){
  $verification_code = $_GET['code'];
}

$query="SELECT email FROM users WHERE verification_code = ?";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("s",$verification_code);
$sql_sec->execute();
$result = $sql_sec->get_result();
$fetch = $result->fetch_array(MYSQLI_ASSOC);
$user_email = $fetch['email'];
$_SESSION['verify']=$verification_code;

if(mysqli_num_rows($result)){

  //update verification code

  $length = 32;
  $verification_code = substr(str_shuffle(md5(time())),0,$length);

  //update verification code

  $query="UPDATE users SET verification_code = ? WHERE email = ?";
  $sql_sec=$db_connect->prepare($query);
  $sql_sec->bind_param("ss",$verification_code, $user_email);
  $sql_sec->execute();
  $success='change?result=change';
  $_SESSION['current_page'] = 'check_change';
  $_SESSION['email'] = $user_email;
  header('location: '.$success);
  die();
}

else{
  $fail='forgot?result=fail_change';
  $_SESSION['current_page'] = 'check_change';
  header('location: '.$fail);
  die();
}

?>

<?php
include 'sendMail.php';
include 'dbconn.php';
session_start();

if(isset($_SESSION['current_page'])){
  $prev_page=$_SESSION['current_page'];
  $_SESSION['current_page'] = pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME);
}
else{
  $prev_page='';
}

if(empty($_POST)){
  header('location:index?_rdr');
  die();
}
$user_email = $_POST['email'];

//verify if account exists

$query= "SELECT email FROM users WHERE email = ?";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("s",$user_email);
$sql_sec->execute();
$result=$sql_sec->get_result();

if(!mysqli_num_rows($result)){
  header('location:forgot?result=not_found');
  die();
}


//get verification code

$query= "SELECT verification_code FROM users WHERE email = ?";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("s",$user_email);
$sql_sec->execute();
$select_db_vcode=$sql_sec->get_result();
$fetch=$select_db_vcode->fetch_array(MYSQLI_ASSOC);
$verification_code = $fetch['verification_code'];

//get first name

$query="SELECT f_name FROM users WHERE email = ?";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("s", $user_email);
$sql_sec->execute();
$result=$sql_sec->get_result();
$get_fname=$result->fetch_array(MYSQLI_ASSOC);
$user_fname=$get_fname['f_name'];

$verification_link="http://localhost/DreamPizza/check_change?code=$verification_code";
$to=$user_email;
$subject="Recover your account";
$message="Hello $user_fname,<br><br>
          <b>Click on the following link to change your password:</b> <a href='".$verification_link."'>$verification_link</a><br><br>
          Thank you for choosing Dream Pizza!";
$nonHTMLMessage = "Hello $user_fname,
                  Click on the following link to change your password go to this URL $verification_link
                  Thank you for choosing Dream Pizza!";

if(sendMail($to, $subject, $message, $header) === 'sent'){
  $_SESSION['current_page']='forgot_pass';
  $_SESSION['email'] = $user_email;
  $success="forgot?result=sent";
  header("location: ".$success);
  die();
}
else{
  $_SESSION['current_page']='forgot_pass';
  $_SESSION['email'] = $user_email;
  $success="forgot?result=fail";
  header("location: ".$success);
  die();
}

?>

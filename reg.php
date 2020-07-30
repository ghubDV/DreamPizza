<?php
include 'dbconn.php';
include 'sendMail.php';
session_start();

//check if the user just entered the address of reg.php without going through register.php -> redirect to register.php

if(empty($_POST)){
  header('Location:register?_rdr');
  die();
}

//user input validation

$user_email=$_POST['email'];
$user_fname=$_POST['f_name'];
$user_lname=$_POST['l_name'];
$user_pass=$_POST['pwd'];
$check_pass=$_POST['re_pwd'];
$user_twitter=$_POST['Twitter'];
$user_addr=$_POST['Address'];
$user_country=$_POST['Country'];
global $registered;
$registered="";

if(empty($user_email) || empty($user_fname) || empty($user_lname) || empty($user_pass) || empty($user_addr) || empty($user_country)){
  $_SESSION['current_page']='reg';
  $empty_fields="register?result=empty";
  header("location: ".$empty_fields);
  die();
}

// input validation function

  function valid_user(){
    $valid_email=False;
    $valid_pass=False;
    global $db_connect;
    global $registered;

    //validate e-mail

    if(isset($_POST['email'])) {
      $user_email = $_POST['email'];
    }

    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
      $_SESSION['current_page']='reg';
      $inv_email="register?result=invalid_email";
      header("location: ".$inv_email);
      die();
    }

    //check if an indentical e-mail is found in db
    //$sql_sec prevents SQL injection

    $query= "SELECT email FROM users WHERE email = ? ";
    $sql_sec=$db_connect->prepare($query);
    $sql_sec->bind_param("s",$user_email);
    $sql_sec->execute();
    $select_db_email=$sql_sec->get_result();

    if(mysqli_num_rows($select_db_email)){
      $_SESSION['current_page']='reg';
      $used_email="register?result=used_email";
      header("location: ".$used_email);
      die();
    }

    else{
      $valid_email=True;
    }

    //check if the password is valid

    if(isset($_POST['pwd'])) {
      $user_pass = $_POST['pwd'];
    }

    if(isset($_POST['re_pwd'])) {
      $check_pass = $_POST['re_pwd'];
    }

    if(strlen($user_pass)>=4 && $check_pass==$user_pass){
      $valid_pass=True;
    }

    else if(strlen($user_pass)<4){
      $_SESSION['current_page']='reg';
      $short_pwd="register?result=short_pass";
      header("location: ".$short_pwd);
      die();
    }

    else if($check_pass!=$user_pass){
      $_SESSION['current_page']='reg';
      $match_pwd="register?result=match_pass";
      header("location: ".$match_pwd);
      die();
    }

    //check if the other fields are valid

    if(isset($_POST['f_name'])) {
      $user_fname = $_POST['f_name'];
    }

    if(isset($_POST['l_name'])) {
      $user_lname = $_POST['l_name'];
    }

    if(isset($_POST['Address'])) {
      $user_addr = $_POST['Address'];
    }

    if(isset($_POST['Country'])) {
      $user_country = $_POST['Country'];
    }

    if(strlen($user_country)<4){
      $_SESSION['current_page']='reg';
      $inv_country="register?result=invalid_country";
      header("location: ".$inv_country);
      die();
    }

    //confirm user inputs

    if($valid_email==True && $valid_pass==True){
      return true;
    }
    else {
      return false;
    }
}

//insert user data into the db if user inputs are validated

if(valid_user()){

  //password hashing

  $hash=password_hash($user_pass, PASSWORD_DEFAULT);

  //account activation code

  $length = 32;
  $verification_code = substr(str_shuffle(md5(time())),0,$length);

  //date of registration

  date_default_timezone_set("Europe/Bucharest");
  $datetime= date('Y-m-d H:i:s');

  //insert user

  if(!isset($_POST['Twitter'])){

    $query= "INSERT INTO users (email, password, f_name, l_name, country, address, verification_code, join_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $sql_sec=$db_connect->prepare($query);
    $sql_sec->bind_param("ssssssss",$user_email, $hash, $user_fname, $user_lname, $user_country, $user_addr, $verification_code, $datetime);
    $sql_sec->execute();
    $insert_user=$sql_sec->get_result();
  }
  else{

    $query= "INSERT INTO users (email, password, f_name, l_name, twitter_name, country, address, verification_code, join_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $sql_sec=$db_connect->prepare($query);
    $sql_sec->bind_param("sssssssss",$user_email, $hash, $user_fname, $user_lname, $user_twitter, $user_country, $user_addr, $verification_code, $datetime);
    $sql_sec->execute();
    $insert_user=$sql_sec->get_result();
  }

}

//send verification e-mail


$verification_link="http://localhost/DreamPizza/activate?code=$verification_code";
$class="link-mail";
$to=$user_email;
$subject="Activate your Dream Pizza account";
$message="Hello $user_fname,<br><br>
          Your account at Dream Pizza was created successfully!<br>
          To access your account you need to activate it!<br>
          <b>This link will expire in an hour and your account will be deleted.</b><br><br>
          <b>Click on the following link to activate your account:</b> <a href='".$verification_link."'>$verification_link</a><br><br>
          Thank you for choosing Dream Pizza!";

$nonHTMLMessage = "Hello $user_fname
                  Your account at Dream Pizza was created successfully!
                  To access your account you need to activate it!
                  This link will expire in an hour and your account will be deleted.
                  Go to the following link to activate your account: $verification_link
                  Thank you for choosing Dream Pizza!";

if(sendMail($to, $subject, $message, $nonHTMLMessage) === 'sent'){
  $_SESSION['current_page']='reg';
  $_SESSION['email'] = $user_email;
  $success="register?result=success";
  header("location: ".$success);
  die();
}
else{
  echo "Error sending activation mail";
  die();
}

?>

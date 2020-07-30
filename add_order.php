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

if(!isset($_SESSION['email'])){
  header('location:'.'index?_rdr');
  die();
}

$limit=$_SESSION['limit'];
$index=1;

while($index<=$limit){
  if(isset($_POST[$index]) && $_POST[$index]>25){
    $err='your_cart?error=exceeded';
    header('location:'.$err);
    die();
  }
  else if(isset($_POST[$index]) && $_POST[$index]<=0){
    $err='your_cart?error';
    header('location:'.$err);
    die();
  }
  $index++;
}

$index=1;

unset($_SESSION['nr_cart']);
while($index<=$limit){
  if(isset($_SESSION['prod_nr'][$index]) && isset($_POST[$index])){
    $_SESSION['prod_price'][$index]=$_POST[$index]*$_SESSION['individual_price'][$index];
    $_SESSION['prod_nr'][$index]=$_POST[$index];
    $_SESSION['nr_cart']+=$_POST[$index];
    $total_price += $_SESSION['prod_price'][$index];
  }
  $index++;
}

$limit=$_SESSION['limit'];
$index=1;
$first=true;

while($index<=$limit){
  if(isset($_POST[$index]) && $first==true){
    $products_ordered.=$_POST[$index].' x Pizza '.$_SESSION['prod_name'][$index];
    $first=false;
  }
  else if(isset($_POST[$index]) && $first==false){
    $products_ordered.=', '.$_POST[$index].' x Pizza '.$_SESSION['prod_name'][$index];
  }
  $index++;
}

if(!isset($products_ordered)){
  header('location:'.'index?_rdr');
  die();
}

//insert order into db

$user_email=$_SESSION['email'];

$query="INSERT INTO orders (email, products_ordered, total_price) VALUES (?, ?, ?)";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("ssi", $user_email, $products_ordered, $total_price);
$sql_sec->execute();
$result=mysqli_affected_rows($db_connect);


if($result){
  $success='your_cart?order=success';
  header('location:'.$success);
  die();
}
else{
  $fail='your_cart?order=fail';
  header('location:'.$fail);
  die();
}

?>

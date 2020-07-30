<?php
session_start();

if(isset($_SESSION['current_page'])){
  $prev_page=$_SESSION['current_page'];
  $_SESSION['current_page'] = pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME);
}
else{
  $prev_page='';
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
  }
  $index++;
}

header('location:'.$prev_page);
?>

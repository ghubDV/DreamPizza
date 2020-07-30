<?php
  session_start();

  if(isset($_SESSION['current_page'])){
    $prev_page=$_SESSION['current_page'];
    $_SESSION['current_page'] = pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME);
  }
  else{
    $prev_page='';
  }

  $item_name=$_GET['del'];
  $index=$_GET['i'];
  $_SESSION['nr_cart']-=$_SESSION['prod_nr'][$index];
  unset($_SESSION['prod_count'][$index]);
  unset($_SESSION['prod_nr'][$index]);
  unset($_SESSION['prod_price'][$index]);
  unset($_SESSION['max'][$index]);
  header('location:'.$prev_page);
?>

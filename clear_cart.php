<?php
session_start();

  if(isset($_SESSION['current_page'])){
    $prev_page=$_SESSION['current_page'];
    $_SESSION['current_page'] = pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME);
  }
  else{
    $prev_page='';
  }
unset($_SESSION['add']);
unset($_SESSION['prod_count']);
unset($_SESSION['tab_count']);
unset($_SESSION['nr_cart']);
unset($_SESSION['prod_price']);
unset($_SESSION['max']);
header('location:'.$prev_page);
?>

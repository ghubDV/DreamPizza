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

$query="SELECT * FROM products ORDER BY id ASC";
$sql_sec=$db_connect->prepare($query);
$sql_sec->execute();
$result=$sql_sec->get_result();
$found=false;
$i=1;
while($fetch=$result->fetch_array(MYSQLI_ASSOC)){
  $prodname_array[$i] = $fetch['product_name'];
  $prodprice_array[$i] = $fetch['product_price'];
  $i++;
}

$_SESSION['individual_price']=$prodprice_array;

$limit=$i;
$i=1;
$j=1;
while($i<=$limit && $found==false){
  if($prodname_array[$i] == $_GET['product']){
    $found=true;
    if(isset($_SESSION['prod_count'][$i]) && $_SESSION['prod_count'][$i]<25){
      $_SESSION['prod_count'][$i]++;
    }
    else if(isset($_SESSION['prod_count'][$i]) && $_SESSION['prod_count'][$i]==25){
      //limit reached
      break;
    }
    else{
      $_SESSION['prod_count'][$i]=1;
    }

    if(isset($_SESSION['prod_price'][$i])){
      $_SESSION['prod_price'][$i] += $prodprice_array[$i];
    }
    else{
      $_SESSION['prod_price'][$i] = $prodprice_array[$i];
    }

  }
  else{
    $i++;
  }
}

if($found == true){
  if(isset($_SESSION['nr_cart']) && $_SESSION['prod_count'][$i]==25 && $_SESSION['max'][$i]==true){
    //limit reached
  }
  else if(isset($_SESSION['nr_cart']) && $_SESSION['prod_count'][$i]==25){
    $_SESSION['nr_cart']++;
    $_SESSION['max'][$i]=true;
  }
  else if(isset($_SESSION['nr_cart']) && $_SESSION['prod_count'][$i]<25){
    $_SESSION['nr_cart']++;
  }
  else if(!isset($_SESSION['nr_cart'])){
    $_SESSION['nr_cart']=1;
  }
  $_SESSION['prod_name']=$prodname_array;
  $_SESSION['prod_nr']=$_SESSION['prod_count'];
  $_SESSION['limit']=8;
  $store='store';
  header('location: '.$store);
  die();
}
else{
  $store='store?_rdr';
  header('location: '.$store);
  die();
}

?>

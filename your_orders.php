<?php
include 'dbconn.php';
session_start();

//check if twitter can be reached

function conn_twitter(){
  $connection=@fsockopen("www.twitter.com",80);

  if($connection){
    $is_connected=true;
    fclose($connection);
  }
  else{
    $is_connected=false;
  }

  return $is_connected;
}


if($db_connect->connect_errno){
    die("Error trying to connect to database");
}

if(!isset($_SESSION['email'])){
  header('location:'.'index?_rdr');
  die();
}

if(isset($_SESSION['get_tweet']) && $_SESSION['get_tweet']==true && conn_twitter()){
  $_SESSION['get_tweet']=false;
  header('location:get_tweets');
}
else if(isset($_SESSION['get_tweet']) && $_SESSION['get_tweet']==false && conn_twitter()){
  $_SESSION['get_tweet']=true;
}


$user_email=$_SESSION['email'];
$fname=$_SESSION['username'];
$query="SELECT * FROM orders WHERE email = ?";
$sql_sec=$db_connect->prepare($query);
$sql_sec->bind_param("s",$user_email);
$sql_sec->execute();
$result=$sql_sec->get_result();

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/fontawesome.js"></script>
	<script src="js/scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="fonts/fonts.css">
	<link rel="stylesheet" type="text/css" href="css/textformatting.css">
	<link rel="stylesheet" type="text/css" href="css/containers.css">
	<link rel="shortcut icon" type="image/x-icon" href="photos/tabicon.ico" />
	<title>Dream Pizza | Your online pizza buddy</title>
</head>
<body>

<!-- Header of the page -->

<div class="container-fluid">
	<div class="title-box">
		<header class="lato-title"><h2 class="head"><a href="index" style="color:white;text-decoration:none"><strong>Dream Pizza</strong></a>
			<img class="logo" src="photos/pizza_rot.png">
			<span id="moto">Your online pizza buddy</span>
		</h2>
		</header>
	</div>
</div>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-md bg-light navbar-light sticky-top">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
	<ul class="navbar-nav mr-auto">
		<li class="nav-item">
			<a class="nav-link" href="index">Home</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="store">Store</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="twitter_order">Order with <i class="fab fa-twitter" style="color:#1da1f2 !important;"></i></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="contact">Contact</a>
		</li>

		<!-- if user is logged in show this menu -->

		<?php
		if(isset($_SESSION['current_page'])){
      $prev_page=$_SESSION['current_page'];
      $_SESSION['current_page'] = pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME);
    }
    else{
      $prev_page='';
		}

		if(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'])>1800){
			session_unset();
			session_destroy();
		}
		else if(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'])<1800){
			$_SESSION['last_activity']=time();
		}
		else if(!isset($_SESSION['last_activity'])){
			$_SESSION['last_activity']=time();
		}

		?>
	</ul>
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" href="logout">Log out</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">My account</a>
				<div class="dropdown-menu">
					<a class="dropdown-item disabled">Hi, <?php echo" $fname"; ?></a>
					<a class="dropdown-item" href="your_orders">Orders</a>
					<a class="dropdown-item" href="myaccount">Account details</a>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown"><?php if(isset($_SESSION['nr_cart']) && $_SESSION['nr_cart']==1){echo $_SESSION['nr_cart'].' item in ' ;}else if(isset($_SESSION['nr_cart']) && $_SESSION['nr_cart']>1){echo $_SESSION['nr_cart'].' items in ';} else{?> No items in<?php } ?> <i class="fas fa-shopping-cart"></i></a>
				<div class="dropdown-menu dropdown-menu-right">
					<a class="dropdown-item disabled" style="text-align:center !important;"><?php if(isset($_SESSION['nr_cart']) && $_SESSION['nr_cart']>0){echo "Current products in your cart" ;} else if(!isset($_SESSION['nr_cart']) || $_SESSION['nr_cart']==0){?>No products in your cart <?php } ?></a>
					<?php
						if(isset($_SESSION['nr_cart']) && $_SESSION['nr_cart']>0){
							$total_price=0;
							$index=1;
							$limit=$_SESSION['limit'];
							while($index<=$limit){
								if(isset($_SESSION['prod_nr'][$index])){
									echo'<a class="dropdown-item disabled" style="text-align:center !important;">'.$_SESSION['prod_nr'][$index].' x Pizza '.$_SESSION['prod_name'][$index].'<span onclick="window.location.href=\'delete_item?del='.$_SESSION['prod_name'][$index].'&i='.$index.'\'" class="clear-item">&times;</span></a>';
									if(isset($_SESSION['prod_price'][$index])){
										$total_price += $_SESSION['prod_price'][$index];
									}
								}
								$index++;
							}
					?>
					<a class="dropdown-item disabled" style="text-align:center !important; border-top: 1px solid #F0F0F0 !important;"><strong>Total: </strong>
						$<?php
							echo $total_price;
						?>
					</a>
					<a class="dropdown-item" style="text-align:center !important; border-top: 1px solid #F0F0F0 !important;" href="clear_cart">Clear cart</a>
					<a class="dropdown-item" style="text-align:center !important; border-top: 1px solid #F0F0F0 !important;" href="your_cart">Cart details</a>
				<?php } ?>
				</div>
		</li>
	</ul>
	</div>
</nav>

<!-- Space between header and content of the site -->

<div class="spacer"></div>

<!-- Content -->

<?php
  if(!mysqli_num_rows($result)){
?>

<div class="f_spacer"></div>
<div class="container">
  <span class="body-title" style="width:100% !important;display:block;text-align:center;">You have no orders currently!</span>
  <p class="body-title" align="center"><a href="store" class="link-emptycart">Go Shopping!</a></p>
</div>

<div class="f_spacer"></div>

<?php } else { ?>

<div class="container">
  <span class="body-title" style="width:100% !important;display:block;text-align:center;">Order History</span>
  <div class="spacer"></div>
  <div class="table-container">
    <table>
      <th>Order Number</th>
      <th>Products Ordered</th>
      <th>Total Price</th>
      <th>Date</th>
      <th>Order Status</th>
        <?php
          while($row=$result->fetch_array(MYSQLI_ASSOC)){
            if($row['order_status']==0){
              $status='Order Received';
            }
            else if($row['order_status']==1){
              $status='Processing';
            }
            else if($row['order_status']==2){
              $status='Sent';
            }
            else{
              $status='Completed';
            }
            echo'
            <tr>
              <td>ORD'.$row['id'].'</td>
              <td>'.$row['products_ordered'].'</td>
              <td>'.$row['total_price'].'$</td>
              <td>'.$row['datetime'].'</td>
              <td>'.$status.'</td>

            </tr>
            ';
          }
        ?>
    </table>
  </div>
</div>
<?php } ?>

<div class="f_spacer"></div>

<!-- Footer -->

<div class="container-fluid" style="background-color: #191919;z-index:3;position:absolute;bottom:0;">
	<div class="container">
	<div class="footer">
		<ul class="foot"><li class="foot-title"><a href="index" style="color:white;text-decoration:none;float:left;padding-right:2px;"><strong>Dream Pizza</strong></a><img class="logo-footer" src="photos/pizza_rot.png"></li></ul>
	  <ul class="foot-nav">
			<li class="foot-item"><a href="index" class="link-nav">Home</a></li>
			<li class="foot-item"><a href="store" class="link-nav">Store</a></li>
			<li class="foot-item"><a href="contact" class="link-nav">Contact</a></li>

			<!-- Replace log in button with log out if user is logged in -->

		<?php
				if(isset($_SESSION['username'])){
		?>
			<li class="foot-item"><a href="logout" class="link-nav">Log out</a></li>
		<?php } else { ?>
			<li class="foot-item"><a href="login" class="link-nav">Log in</a></li>
		<?php } ?>
		</ul>
	</div>
	</div>
</div>
</body>
</html>

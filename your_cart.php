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
	<script src="js/jquery-ui.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
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
		session_start();
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

		if(isset($_SESSION['username'])){
			$fname=$_SESSION['username'];
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
				<a class="nav-link" href=""><?php if(isset($_SESSION['nr_cart']) && $_SESSION['nr_cart']==1){echo $_SESSION['nr_cart'].' item in ' ;}else if(isset($_SESSION['nr_cart']) && $_SESSION['nr_cart']>1){echo $_SESSION['nr_cart'].' items in ';} else{?> No items in<?php } ?> <i class="fas fa-shopping-cart"></i></a>
			</li>
	</ul>
	</div>
</nav>

<!-- if user is not logged in redirect to index -->

<?php } else {
  header('location:index?_rdr');
  die();
}

?>

<?php if(isset($_GET["order"]) && $_GET["order"]=="success" && $prev_page=='add_order' && isset($_SESSION['nr_cart'])) {
		$_SESSION['order']=true;
		header('location:'.'clear_cart');
	}
	?>

<!-- if user has products in cart -->

<?php
	if(isset($_SESSION['nr_cart']) && $_SESSION['nr_cart']>0){
?>

<div class="spacer"></div>

<div class="container" style="height:125px !important;">
	<div style="display:block;position:relative;"><p class="body-title">Current products in your cart</p></div>
</div>

<!-- products in cart -->

<div class="container">
	<?php
		if (isset($_GET["error"]) && $_GET["error"]=="exceeded" && ($prev_page=='calculate' || $prev_page=='add_order')) {
	?>
			<div id="error-reg">
				<span id="closebtn">&times;</span>
					Product limit is 25 per item!
			</div>
			<div id="alert-spacer"></div>
	<?php } ?>
	<form action="add_order.php" method="post">
	<?php
	$total_price=0;
		$index=1;
		$limit=$_SESSION['limit'];
		while($index<=$limit){
			if(isset($_SESSION['prod_nr'][$index])){
				echo'
						<div class="row">
							<div class="col-md">
									<div class="store-item">
										<span class="item-description">Pizza ',$_SESSION['prod_name'][$index],'</span>
										<span onclick="window.location.href=\'delete_item?del='.$_SESSION['prod_name'][$index].'&i='.$index.'\'" class="item-ico"><i class="fas fa-times fa-xs"></i></span>
										<span class="item-price">',$_SESSION['prod_price'][$index],'$</span>
										<span class="item-qty">
											<input type="qty" class="form-control" name="',$index,'" value="',$_SESSION['prod_nr'][$index],'">
										</span>
										<span class="item-price" style="padding:2%;">QTY</span>
									</div>
							</div>
						</div>
					<div class="f_spacer"></div>
				';
				$total_price += $_SESSION['prod_price'][$index];
			}
		$index++;
	}
	$_SESSION['order_price']=$total_price;
?>
<hr>
<div class="row">
	<div class="col-md">
			<div class="store-itemb">
				<span class="item-description">Total Price</span>
				<span class="item-price"><?php echo $total_price,'$'; ?></span>
			</div>
	</div>
</div>
<div class="f_spacer"></div>
	<span id="logbutton-container">
		<button type="submit" id="dream_button-l" style="float:left;margin-left:auto;margin-right:auto;">Order Now!</button><br><br>
		<button type="submit" formaction="calculate.php" id="dream_button-l" style="float:left;margin-left:auto;margin-right:auto;">Calculate Prices!</button><br><br>
	</span>
	<span id="logbutton-container-m">
		<button type="submit" formaction="calculate.php" id="dream_button" style="float:left;width:100%;">Calculate Prices!</button><br><br>
		<button type="submit" id="dream_button" style="float:left;width:100%;">Order Now!</button><br><br>
	</span>
	<div class="f_spacer"></div>
	</form>
</div>

<!-- if user doesn't have products in cart -->
<?php } else if(isset($_SESSION['order'])) {
		unset($_SESSION['order']);
	?>

<div class="f_spacer"></div>
<div id="alert-spacer"></div>
<div class="container" style="height:100% !important;">
	<div id="success-reg">
		<span id="closebtn">&times;</span>
			Order successfully sent!
	</div>
	<div id="alert-spacer"></div>
	<p class="body-title" align="center">You have no products in your cart!</p>
	<p class="body-title" align="center"><a href="store" class="link-emptycart">Go Shopping!</a></p>
</div>


<?php }else { ?>

<div class="f_spacer"></div>
<div class="container" style="height:100% !important;">
	<p class="body-title" align="center">You have no products in your cart!</p>
	<p class="body-title" align="center"><a href="store" class="link-emptycart">Go Shopping!</a></p>
</div>

<?php } ?>

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

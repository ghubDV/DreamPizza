<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/jquery-3.3.1.min.js"></script>
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
			<span id="moto"></span>
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

<!-- if user is not logged in show this menu -->

		<?php } else { ?>
			<li class="nav-item">
				<a class="nav-link" href="login">Log in</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="register">Register</a>
			</li>
		</ul>
	</div>
</nav>

<?php } ?>

<!-- Space between header and content of the site -->

<div class="spacer"></div>

<div class="container">

<!-- Image slider -->


	<!-- Image slider indicators -->

	<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  	<ol class="carousel-indicators">
    	<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    	<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    	<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  	</ol>

		<!-- Slide contents -->

  	<div class="carousel-inner" role="listbox">
    <div class="carousel-item active">
      <img class="d-block img-fluid" src="photos/slide1.jpg" alt="First slide">
    </div>
    <div class="carousel-item">
      <img class="d-block img-fluid" src="photos/slide2.jpg" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block img-fluid" src="photos/slide3.jpg" alt="Third slide">
    </div>
  	</div>

		<!-- Prev&Next slide -->

  	<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    	<span class="carousel-control-prev-icon" aria-hidden="true"></span>
    	<span class="sr-only">Previous</span>
  	</a>
  	<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    	<span class="carousel-control-next-icon" aria-hidden="true"></span>
    	<span class="sr-only">Next</span>
  	</a>
	</div>
</div>

<!-- body-text -->

<div class="container"><br><br><br>
	<p class="body-title">Welcome to Dream Pizza!</p><br>
	<p class="body-text">
		<ul>
			<li>Are you looking for an unforgettable pizza experience?</li>
			<li>Do you want a fresh,homemade and delicious pizza to be delivered fast at your place?</li>
			<li>Are you looking for a trustworthy pizza place that can deliver your meal anywhere?</li>
		</ul>
	</p><br>
	<p class="body-text">
		Welcome to a pizza place that anyone can enjoy! From family nights out to going out with your friends, Dream Pizza is the perfect place to spend any night during the week or the weekend. If you want to come have a slice, then come visit us on Mihai Eminescu street, Ploiesti, Romania.<br><br>
		Inspired by the love for food and people, the first Dream Pizza pizzeria was opened on June 4, 1998. It was built upon traditional principles and a commitment to quality, service, and long-standing relationships with communities, customers, and employees.<br><br>
		I grew up in a Romanian neighborhood where the emphasis on food making was its simplicity and flavor. I remember the aroma of fresh sauce being simmered at our home; the pungent smell of freshly grated cheese as my mother sprinkled it on the pasta; and the warmth and love that exuded from the dinner table as the family ate together.<br><br>
		At Dream Pizza pizzeria, we serve generous portions of food, made with quality ingredients by people who care. We are not fancy, we just serve great food. We are not about sun-dried tomatoes, wood-burning ovens, and $10 mushrooms. We shine through our simple and delicious food.<br><br>
		Now, with our new order system we can deliver your pizza to your place faster than ever before. You can create an account to order or tweet to us your order through your twitter account<br><br>
		If for any reason we fail to meet your expectations, please email us at officeDreamPizza@gmail.com<br><br>
		Thank you for choosing Dream Pizza!<br><br>
	</p>
</div>

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

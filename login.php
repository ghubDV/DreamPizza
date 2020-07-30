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
	<?php
			session_start();
			if(isset($_SESSION['current_page'])){
				$prev_page=$_SESSION['current_page'];
				$_SESSION['current_page'] = pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME);
			}
			else{
				$prev_page='';
			}
			if(isset($_SESSION['username'])){
				header('location:'.$prev_page);
			}
	 ?>
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
	<ul class="navbar-nav">
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
		<li class="nav-item">
			<a class="nav-link" href="login">Log in</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="register">Register</a>
		</li>
	</ul>
	</div>
</nav>

<!-- Space between header and content of the site -->

<div class="spacer"></div>

<!-- Log-in form -->

<div class="container">
  <span class="body-title" style="display:block;text-align:center;">Enter your account information</span>
  <div class="spacer"></div>
  <div class="form-container">
    <form action="log.php" method="post">
      <div class="form-group">
				<?php
					if (isset($_GET["result"]) && $_GET["result"]=="activated" && $prev_page=='activate') {
				?>
						<div id="success-reg">
							<span id="closebtn">&times;</span>
								Account activated! You can log-in
						</div>
						<div id="alert-spacer"></div>
				<?php
			} else if (isset($_GET["result"]) && $_GET["result"]=="not_activated" && $prev_page=='activate') {
				?>
						<div id="error-reg">
							<span id="closebtn">&times;</span>
								Failed to activate or account already activated
						</div>
						<div id="alert-spacer"></div>
				<?php
			} else if(isset($_GET["result"]) && $_GET["result"]=="fail" && $prev_page=='log') {
			 	?>
						<div id="error-reg">
							<span id="closebtn">&times;</span>
								Email or password incorrect!
						</div>
						<div id="alert-spacer"></div>
				<?php
			} else if(isset($_GET["result"]) && $_GET["result"]=="not_active" && $prev_page=='log') {
				?>
						<div id="error-reg">
							<span id="closebtn">&times;</span>
								Your account is not activated!
						</div>
						<div id="alert-spacer"></div>
				<?php
			} else if(isset($_GET["result"]) && $_GET["result"]=="changed" && $prev_page=='change_pass') {
				?>
						<div id="success-reg">
							<span id="closebtn">&times;</span>
								Your password was updated!
						</div>
						<div id="alert-spacer"></div>
				<?php
					session_destroy();
				 ?>
				<?php
		 	}	else if(isset($_GET["to"]) && $_GET["to"]=="store") {
		 		?>
		 				<div id="success-reg">
		 					<span id="closebtn">&times;</span>
		 						You need to log in to your account!
		 				</div>
		 				<div id="alert-spacer"></div>
				<?php } ?>
        <label for="email"><strong>Email:</strong></label>
        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
      </div>
      <div class="form-group">
        <label for="pwd"><strong>Password:</strong></label>
        <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd">
      </div>
      <span id="logbutton-container">
        <button type="submit" id="dream_button-l" style="float:left;margin-left:auto;margin-right:auto;">Log in</button>
      </span>
      <span id="logbutton-container-m">
        <button type="submit" id="dream_button" style="float:left;width:100%;">Log in</button><br><br>
      </span>
    </form>
  </div>
  <div class="f_spacer"></div>

	<div id="register-container">
    <span class="body-title">Don't have an account?</span><br><br>
    <div style="padding:0px 30px;"><button id="register_button" onclick="window.location.href='register'">Create an account!</button></div>
	</div>
	<div id="register-container-m">
    <span class="body-title">Don't have an account?</span><br><br>
    <div style="padding:0;"><button id="register_button" onclick="window.location.href='register'">Create an account!</button></div>
  </div>

	<div class="spacer"></div>

	<div id="register-container">
		<span class="body-title">Forgot your password?</span><br><br>
		<div style="padding:0px 30px;"><button id="register_button" onclick="window.location.href='forgot'">Recover account</button></div>
	</div>
<div id="register-container-m">
		<span class="body-title">Forgot your password?</span><br><br>
		<div style="padding:0;"><button id="register_button" onclick="window.location.href='forgot'">Recover account</button></div>
</div>
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
			<li class="foot-item"><a href="login" class="link-nav">Log in</a></li>
			<li class="foot-item"><a href="contact" class="link-nav">Contact</a></li>
		</ul>
	</div>
	</div>
</div>
</body>
</html>

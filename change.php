<!doctype html>
<?php
    session_start();
    if(isset($_SESSION['current_page'])){
      $prev_page=$_SESSION['current_page'];
      $_SESSION['current_page'] = pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME);
    }
    else{
      $prev_page='';
    }
    if (isset($_SESSION['verify']) && ($prev_page=='check_change' || $prev_page==$_SESSION['current_page'])) {
      //continue
    }

    else if(isset($_SESSION['verify']) && ($prev_page=='change_pass' || $prev_page==$_SESSION['current_page'])) {
      //continue
    }

    else{
      header('location:index?_expired');
    }
 ?>
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
  <span class="body-title" style="display:block;text-align:center;">Change your password</span>
  <div class="spacer"></div>
  <div class="form-container">
    <form action="change_pass.php" method="post">
      <div class="form-group">
        <?php
					if (isset($_GET["result"]) && $_GET["result"]=="fail" && $prev_page=="change_pass") {
				?>
						<div id="error-reg">
							<span id="closebtn">&times;</span>
								Passwords don't match!
						</div>
						<div id="alert-spacer"></div>
        <?php
      } else if (isset($_GET["result"]) && $_GET["result"]=="empty" && $prev_page=="change_pass") {
        ?>
            <div id="error-reg">
              <span id="closebtn">&times;</span>
                Empty Fields!
            </div>
            <div id="alert-spacer"></div>
        <?php
     } else if (isset($_GET["result"]) && $_GET["result"]=="short" && $prev_page=="change_pass") {
        ?>
            <div id="error-reg">
              <span id="closebtn">&times;</span>
                Password should be atleast 4 characters!
            </div>
            <div id="alert-spacer"></div>
        <?php } ?>
          <label for="pwd"><strong>Password:</strong></label>
          <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd">
        </div>
        <div class="form-group">
          <label for="pwd"><strong>Confirm password:</strong></label>
          <input type="password" class="form-control" id="re_pwd" placeholder="Re-enter password" name="re_pwd">
        </div>
      <div style="width:100%;height:30px;"></div>
      <span id="logbutton-container">
        <button type="submit" id="dream_button-l" style="float:left;margin-left:auto;margin-right:auto;">Change</button>
      </span>
      <span id="logbutton-container-m">
        <button type="submit" id="dream_button-l" style="float:left;width:100%;">Change</button>
      </span>
      </div>
    </form>
  <div class="f_spacer"></div>
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

<?php
  session_start();
  session_unset();
  session_destroy();
  $home="index";
  header('location: '.$home);
 ?>

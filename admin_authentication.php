<?php 
  session_start(); 

  if (!isset($_SESSION['Ausername'])) {
  	$_SESSION['msg'] = "You must login to gain access to Administrator panel.";
  	header('location: adminlogin.php');
  }
  if (isset($_GET['logout'])) {
    session_unset();
  	session_destroy();
  	header("location: adminlogin.php");
  }
  header("Location: adminpanel.php")
  ?>
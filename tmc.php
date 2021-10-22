<?php 
session_start(); 
  

  
  $ypos = $_COOKIE["ypos"];
            
?>
    
<!-- Welcome PAGE -->
<!-- required html -->
<!doctype html>
<html lang="en">
<head>
    <title>Post17 by FringeLogic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body style="background: transparent">
        <style>
            .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary
            {
                background-color:#8FBC8F;
                border-color: #8FBC8F;
                color:#fff;
            }
            .btn-primary
            {
                color:#fff;
                background-color:#20B2AA;
                border-color: #20B2AA;
            }
    </style>

    <?php include "includes/carousel.html" ;
    include "includes/regnav.html";?>
    <br><br><br>
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-lg-4">
        <!-- Login Form -->
       <form class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body" method="post" style="padding:10px";>
  <div class="mb-3">
    <center>
      <IMG id="namebanner" SRC="images/logo.png" width="80%" >
         </center>
          <?php include('errors.php'); ?>
          <div class="text-left">
           <center>   
          <h4 class="modal-title">Post 17 Terms and Conditions</h4></center>
                  <div class="mb-3">
                    <br><p>By signing up to Post17, you agree to the following terms of use: <br>  
                            You are above 12 years of age <br>
                            You intend to follow Post17's posting guidelines which only allow posts of nature, objects and animals. <br>
                            Any posts that do not comply with our guidelines will be removed by our admins. 
                            <center>-Fringe Logic</center></p>
                  </div>
    </div>
    <div class="col col-lg-2">
    </div>
  </div>
</div>
        <br>
        <br>
        <br>
    <nav class="navbar fixed-bottom navbar-light bg-success bg-gradient ">
  <div class="container-fluid ">
      
    <a class="navbar-brand" href="#">© Fringelogic Group 17</a>
    <a class="navbar-text navbar-right">Powered by Bootstrap</a>
  </div>
</nav>
    <br><br>

</body>
</html>
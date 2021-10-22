<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<!-- CHALLENGES PAGE -->

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
    <?php include "includes/challengesheader.html" ?>
    <?php include "includes/carousel.html" ?>
    <!-- each challenge will have its own card, just setting rest of pages to standard layout for now -->
    
    <div class="container">
        <?php
         /*echo "<br><form method=\"post\" action=\"$previous_location\">
                    <input type=\"submit\" value=\"back\" name=\"back\" >
                    </form>";*/
            ?>
        <br>
        <div class = "row row-cols-3">
            
            <div class = "col-lg-3 col-md-5 col-6 d-block">
            <div class = "card border-2 border-dark shadow-lg">
                
            <div class = "card body border-0 m-2" align="center">
               <h2>Challenges</h2>
             </div>
            </div>
            </div>
            
            <div class = "col-lg-5 col-md-4">
            </div>
            
            <div class = " d-none d-md-block col-lg-3 col-md-4">
                <div class = "card border-2 border-dark shadow-lg">
                <div class = "card body border-0 m-2" align="center">
                <h2>Notifications</h2>
                </div>
                </div>
            </div>
        </div><br>
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
        <div class="row row-cols-2">
        <div class="col-md-8 col-12 d-block">
        <div class="border border-0 rounded-3  mb-5 bg-transparent">
            <div class = "card border-dark border-2">
                <div class = "card body border-0">
                    <ul class="list-group list-group-flush border-0">
                        <li class="list-group-item border-0">
                            
                        <!--</li>
                        <li class="list-group-item">-->   
                            <p>Posts with the most likes on the challenge end date wins the challenge.
                                <br><br>To upload a participating post to a challenge, go to 'Create New Post' and select a challenge from the dropdown menu for 'Participate in Challenge'</p>
                                <h5>Create a Challenge:</h5>
                            
                            <input type="button" class="btn btn-primary btn-sm"  id="newchallenge" onclick="location.href='createchallenge.php';" value = "Create a Challenge" /><br><br>
                        <!--</li>
                        <li class="list-group-item">-->
                            <h5 id="activechallenges">My Challenges: </h5>
                            <input type="button" class="btn btn-primary btn-sm"  onclick="location.href='mychallenges.php';" value="View My Challenges" />
                            <br><br>
                            <h5 id="activechallenges">Active Challenges: </h5>
                            <input type="button" class="btn btn-primary btn-sm"  onclick="location.href='activechallenges.php';" value="View Active Challenges" />
                            <br><br>
                            <h5 id="upcomingchallenges">Upcoming Challenges: </h5>
                            <input type="button" class="btn btn-primary btn-sm"  onclick="location.href='upcomingchallenges.php';" value="View Upcoming Challenges" />
                            <br><br>
                            <h5 id="expiredchallenges">Expired Challenges: </h5>
                            <input type="button" class="btn btn-primary btn-sm"  onclick="location.href='expiredchallenges.php';" value="View Expired Challenges" />
                        </li><br>
                        </ul>
    
                    </div>
                </div>
            </div>
        </div>
        

    <?php include "includes/notifications.php" ?>

  </div>
</div>

<?php include "includes/footer.php" ?>
<br>
<br>
<br>
</body>
</html>

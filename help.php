<?php 
session_start(); 
  
  
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	unset($_SESSION['previous_location']);
  	unset($_SESSION['page']);
  	header("location: login.php");
  }

  
  $ypos = $_COOKIE["ypos"];
            
?>
    
<!-- HELP POST PAGE -->
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
    <?php include "includes/helpheader.html" ?>
    <?php include "includes/carousel.html" ?>
    <!-- each challenge will have its own card, just setting rest of pages to standard layout for now -->
    
    <div class="container">
        <br>
        <div class = "row row-cols-3">
            
            <div class = "col-lg-3 col-md-5 col-6 d-block">
            <div class = "card border-2 border-dark shadow-lg">
                
            <div class = "card body border-0 m-2" align="center">
               <h2>Frequently Asked Questions:</h2>
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
        
        <div class="row row-cols-2">
        <div class="col-md-8 col-12 d-block">
        <div class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body">
            <div class = "card border-0">
                <div class = "card body border-0">
                    
                    <p>
                        <a style="font-weight:bold;">How can I change my account to a private account?</a><br>To change account details, access the "Account Settings" from the "My Account" page found on the navigation bar.
                        <br><br>
                        <a style="font-weight:bold;">What are the rules for posting pictures?</a><br>We have a strict policy of only allowing posts of nature, objects and animals. If you post images of faces or people, your posts will get removed.
                        <br><br>
                        <a style="font-weight:bold;">I have too many notifications, they are very distracting for me, what can do?</a><br>Simply turn off your notifications, access the "Account Settings" from the "My Account" page found on the navigation bar.
                        <br><br>
                        <a style="font-weight:bold;">How can I stop strangers from posting pictures to my channels?</a><br>If you change your channel to a private channel then only users who have joined the channel will be able to view the posts in the channel and add new posts to the channel.
                        To change channel privacy, access the "My Channels" page from the "Channels" page found on the navigation bar.
                        <br><br>
                        <a style="font-weight:bold;">When should I report a post?</a><br>You should report a post any time you feel it is inappropriate, or disrespectful to users. Also, posts should be reported when they don't follow our strict policy of only allowing posts of nature, objects and animals. 
                        <br><br>
                        <a style="font-weight:bold;">What is a challenge, how does that work?</a><br>Challenges are a fun way to interact with other users on the site. 
                        If you are ever unsure what to post challenges are always a great way to inspire users to go out and take some interesting pictures. To join a pre-existing challenge, access "Active Challenges" from the "Challenges" page found on the navigation bar, or create your own challenge. 
                        The post with the post likes by the challenge end date is the winner of the challenge, good luck!
                        <br><br>
                        <a style="font-weight:bold;">What happens when I report a post?</a><br>When you report a post, our admin team will view the reported post and assess it with regared to the reason given for reporting the post. If we feel that the post is inappropriate, or disrespectful to users then we will repove the post from the site.
                        <br><br>
                        <a style="font-weight:bold;">How can my friends join my private channel?</a><br>Private channels are invitation only. To send channel invites to users which you are following, access the "My Channels" page from the "Channels" page found on the navigation bar, 
                        then choose channel settings on the channel which you would like to invite your friend to join. 
                        </p><br><hr><br><p>
                        If we have not addressed your questions please feel free to email us at <a style="font-weight:bold; text-decoration:underline">fringe17logic@gmail.com</a> we frequently update our FAQ's.<br>
                    </p>
                    <!--
                    <center>
                    <form method="post">
                            <label><a style="font-weight:bold">Get in contact now: </a><br>(please include your email in your message incase we need to get back to you.)</label>
                            
                            <textarea name="contact" rows="7" cols="90"></textarea><br>
                            <button name="verify" class="btn btn-primary btn-sm">Verify</button>
                    </form><br>
                    </center>
                    -->
    
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
</body>
</html>
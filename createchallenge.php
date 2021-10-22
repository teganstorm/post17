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
  
  $errors = array(); 
  
  $ypos = $_COOKIE["ypos"];
  
?>

<?php



    if (isset($_POST['submit']) && ($_POST['submit'] == 'Create Challenge')) {

    $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
    
    
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    $name = ($_POST['name']);
    $startdate = ($_POST['startdate']);
    $enddate = ($_POST['enddate']);
    $description = ($_POST['description']);
    $username = $_SESSION['username'];
        if (empty($username)) {
          	    echo "<br><br><br><br><br>help";
            }
    if(strlen($name)>50){
        array_push($errors ,"The challenge name is too long please keep it below 50 characters!");
    }
    if(strlen($description)>255){
        array_push($errors ,"The challenge description is too long please keep it below 255 characters!");
    }        
    $currDate1 = date("Y-m-d"); //today's date
    $currDate1 = new DateTime($currDate1); // convert into seconds
    
    $startdate1 = new DateTime($startdate); // convert the start date specified by the user into seconds
 

    
    
    if ($currDate1 > $startdate1 ){
      array_push($errors ,"The start date of the challenge must be today, or after!");
    }
  
    $enddate1 = new DateTime($enddate); // convert the start date specified by the user into seconds
    
  if ($startdate1 > $enddate1 ){
      array_push($errors, "The end date of the challenge cannot be before the start date!");
  }
  
    $challenge_data = "SELECT * FROM Challenge" ;
    $results = mysqli_query($db, $challenge_data);
    $challenge_ids = array();
    $challenge_index = 0;
  
        while ($row = mysqli_fetch_array($results)) {
            $temp_CID = $row['challengeID'];
            $challenge_ids[$challenge_index] = $temp_CID;
            $challenge_index++;
        }
        
    $num_desc = $challenge_index;
    $index =0;

    
    while ($index < $num_desc) {
            $tmp_id = $challenge_ids[$index];
            $tmp_challenge_info_query = "SELECT * FROM Challenge WHERE challengeID = '$tmp_id'";
            $results2 = mysqli_query($db, $tmp_challenge_info_query);
            $tmp_challenge_info = mysqli_fetch_assoc($results2);
            $tmp_challengeName = ($tmp_challenge_info['challengeName']);
            if ($tmp_challengeName == $name) {
                array_push($errors, "Cannot include a challenge with a name that already exists!");
                break;
            }
        
     $index++;   
    }
  
    
  //  echo $name;
//    echo $startdate;
 //   echo $enddate;
  // 
  
// echo $description;  
    
     $query = "SELECT * FROM Users WHERE username = '$username' ";
        
        $results = mysqli_query($db, $query);
        
        $user = mysqli_fetch_assoc($results);
        
        $id = ($user['userID']);
        
      //  echo $id;
    
  if (count($errors) == 0) {
    $query2 = "INSERT INTO Challenge (challengeName, startDate, endDate, userID, description) VALUES ('$name','$startdate','$enddate','$id','$description')";
                  $succesfullMessage = "Successfully created challenge!";
            $messageecho = "<h5> <a style=\"color:#20b2aa\"  > <u> $succesfullMessage</u></a> </h5> ";
     if ($db->query($query2) === TRUE) {
             // now add the user to participants
             $q = "SELECT * FROM Challenge WHERE challengeName='$name' AND userID='$id'";
             $results = mysqli_query($db, $q);
             $challenge = mysqli_fetch_assoc($results);
             $challengeid = $challenge['challengeID'];
             $query5 = "INSERT INTO Participants (challengeID, userID) VALUES ('$challengeid', '$id')";
             mysqli_query($db, $query5);
        } else {
            echo "it didn't work" . $query2 . "<br>" . $conn->error;
        }
    } else {
        header(createchallenge.php);
    }
    }


?>
    




<!-- required html -->
<!doctype html>
<html lang="en">
<head>
    <title>Post17 by FringeLogic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
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

</head>


        <body id="body">
            <?php include "includes/header.html" ?>
            <?php include "includes/carousel.html"?>
            <?php    print "<body  onScroll=\"document.cookie='ypos=' + window.pageYOffset\" onLoad='window.scrollTo(0,$ypos)'>"; ?>
            
            <div class="container">
                <br>
                <div class = "row row-cols-3">
            <div class = "col-lg-4 col-md-5 col-7 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Create Challenge</h2>
             </div></div>
            </div>
            <div class = "col-lg-4 col-md-3">
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
            
            <!-- Main content of current page -->
            <div class="col-md-8 col-12 d-block">
            <div class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body">
            

            <form id="form" method="post">
                  <?php        echo $messageecho; ?>
                <label id="nametext">Challenge Name:</label>&nbsp
                 
                <input type="text" id="name" name="name" required><br>
    
                <label id="descriptiontext">Challenge Description:</label>&nbsp
                <input type="text" id="description" name="description" required><br>
    
                <label id="startdatetext">Challenge Start Date:</label>&nbsp
                <input type="date" id="startdate" name="startdate" required><br>
                
                <label id="enddatetext">Challenge End Date:</label>&nbsp
                <input type="date" id="enddate" name="enddate" required><br>
    
                <br>
                <input id="submit" type="submit" name="submit" value="Create Challenge"  class="btn btn-primary btn-sm">
                <?php include('errors.php'); ?>
            </form>
            </div>
            </div>
                <?php include "includes/notifications.php" ?>
            </div>
            </div>
            <?php include "includes/footer.php" ?>

        </body>
        <br>
         <br>
          <br>
</html>


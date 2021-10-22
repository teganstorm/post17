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
  
  $ypos = $_COOKIE["ypos"];
?>

<!doctype html>
<html lang="en">
<head>
    <title>Post17 by FringeLogic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
    <?php include "includes/header.html" ?>
    <?php include "includes/carousel.html" ?>
    <div class="container">
        <br>
        <div class = "row row-cols-3">
            <div class = "col-lg-4 col-md-5 col-7 d-block">
            <div class = "card border-2 border-dark shadow-lg">
            <div class = "card body border-0 m-2" align="center">
               <h2>Change End Date</h2>
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
          
    <div class="col-md-8 col-12 d-block">
    <div class="border border-0 rounded-3 mb-5 bg-body">
            
        <div class = "card border-dark border-2">
        <div class = "card body border-0">
            <ul class="list-group list-group-flush">
            
            <li class="list-group-item border-0">
                <form method="post">
                    <div class="input-group">
                        <br>
                        <label for="birthtext" id="birthtext" class="form-label">New end date: </label> <br>
                        <input type="date" id="newenddate" name="newenddate" required><br>
                    </div>
                
                    <br>
                    <div class="input-group">
                        <input id="submit" type="submit" name="changeenddate" value="Set New Challenge End Date">
                    </div>
                </form>
            </li>
        </div>
        </div>
        
<?php
        $errors = array(); 
        if (isset($_POST['changeenddate']) && ($_POST['changeenddate'] == 'Set New Challenge End Date')) {

            
            $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            
            $challenge_id = $_GET['challenge'];
                        
            $query12 = "SELECT * From Challenge WHERE challengeID='$challenge_id'";
            $result = mysqli_query($conn, $query12);
            $challenge = mysqli_fetch_assoc($result);
            
            $start_date = $challenge['startDate'];
            $startdate1 = new DateTime($start_date);
            
            $end_date = $challenge ['endDate'];
            $enddate1 = new DateTime($end_date);
     
            $currDate = date("Y-m-d"); //today's date
            $currDate1 = new DateTime($currDate); // convert into seconds
            
            $newEndDate = $_POST['newenddate'];

            $newStartDate1 = new DateTime($newStartDate);
            
            if ($newendDate1 > $startdate1 ){
                array_push($errors, "The end date of the challenge cannot be before the start date!");
            }
            
            if(empty($newEndDate)) {
                array_push($errors);
            }

            include('errors.php');
            if (count($errors) == 0) {
          	    $sql = "UPDATE Challenge SET endDate='$newEndDate' WHERE endDate='$end_date'";
         	mysqli_query($conn, $sql);
          	
          	if ($conn->query($sql) === TRUE) {
                echo "End date changed successfully";
            } else {
              echo "Error changing end date: " . $conn->error;
            }
        }

        }
        
        ?>
            
    </div>
    </div>
         <?php include "includes/notifications.php" ?>
    </div>
    </div>
<br>
<br>
<br>
    <?php include "includes/footer.php" ?>
</body>
</html>
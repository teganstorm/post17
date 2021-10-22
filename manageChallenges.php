<?php 
  session_start(); 
  if (!isset($_SESSION['Ausername'])) {
  	$_SESSION['msg'] = "You must login to access Administrator panel.";
  	header('location: adminlogin.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['Ausername']);
  	header("location: adminlogin.php");
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
</head>
<body>
<?php include "includes/adminnav.html" ?>
<?php
echo "<center>";
$db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($db->connect_error) {
                  die("Connection failed: " . $db->connect_error);
        }
$query = "SELECT * FROM Challenge";
$result = mysqli_query($db, $query);

echo "<h2>Manage Challenges</h2>";
echo "<table>
<tr>
<td><h4>Challenge ID</h4></td>
<td><h4>Challenge Name</h4></td>
<td><h4>Start Date</h4></td>
<td><h4>End Date</h4></td>
<td><h4>user ID</h4></td>
<td><h4>Description</h4></td>
<td><h4>Winner user ID</h4></td>
<td><h4></h4></td>
</tr>";

$challengeCount = 0;
$challenge_ids = array();
while ($row = mysqli_fetch_array($result)) {
    $temp_challengeID = $row['challengeID'];
    $challenge_ids[$challengeCount] = $temp_challengeID;
    echo "<tr>";
    echo "<td>". "<input type='text' name='challengeID' value= ".$row['challengeID']."></td>";
    echo "<td>". "<input type='text' name='challengeName' value= ".$row['challengeName']."></td>";
    echo "<td>". " <input type='text' name='startDate' value= ".$row['startDate']."> </td>";
    echo "<td>". " <input type='text' name='endDate' value= ".$row['endDate']."> </td>";
    echo "<td>". " <input type='text' name='userID' value= ".$row['userID']."> </td>";
    echo "<td>". " <input type='text' name='description' value= ".$row['description']."> </td>";
    echo "<td>". " <input type='text' name='winnerUserID' value= ".$row['winnerUserID']."> </td>";
    echo "<td><form method=\"post\" > <input type=\"submit\" style=\"background-color:coral\" value=\"Update\" name=\"update$challengeCount\" onclick=\"return confirm('Are you sure you want to update this challenge?)\"></form>";
    echo "<td><a href=\"deleteChallengeAdmin.php?id=".$row['challengeID']."\">Delete</a></td>";
    $challengeCount++;
    echo "</tr>";
}

    $index = 0;
    for ($index; $index < $challengeCount; $index++) {
        $temp = "delete$index";
        if(isset($_POST[$temp])) {
           
            $remove_challengeid = $challenge_ids[$index];
            // remove post from channel table
       
            $query1 = "DELETE FROM Challenge WHERE challengeID ='$remove_challengeid' ";
            mysqli_query($db,$query1);

            }
        
        }
        
echo "</table>";
echo "</center>";        
header("Refresh:0");
mysqli_close($db);

?>
</div>
<?php include "includes/adminFooter.html" ?>
</body>
</html>
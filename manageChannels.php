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
$query = "SELECT * FROM Channel";
$result = mysqli_query($db, $query);

echo "<h2>Manage Channels</h2>";
echo "<table>
<tr>
<td><h4>Channel ID</h4></td>
<td><h4>User ID</h4></td>
<td><h4>Description</h4></td>
<td><h4>Channel Name</h4></td>
</tr>";

$channelCount = 0;
$channel_ids = array();
while ($row = mysqli_fetch_array($result)) {
    $temp_channelID = $row['channelID'];
    $channel_ids[$channelCount] = $temp_channelID;
    echo "<tr>";
    echo "<td>". "<input type='text' name='channelID' value= ".$row['channelID']."></td>";
    echo "<td>". "<input type='text' name='userID' value= ".$row['userID']."></td>";
    echo "<td>". " <input type='text' name='description' value= ".$row['description']."> </td>";
    echo "<td>". " <input type='text' name='channelName' value= ".$row['channelName']."> </td>";
    echo "<td><form method=\"post\" > <input type=\"submit\" style=\"background-color:coral\" value=\"Update Channel\" name=\"update$channelCount\" onclick=\"return confirm('Are you sure you want to update this channel?)\"></form>";
    echo "<td><a href=\"deleteChannelAdmin.php?id=".$row['channelID']."\">Delete</a></td>";
    $channelCount++;
    echo "</tr>";
}

    $index = 0;
    for ($index; $index < $channelCount; $index++) {
        $temp = "delete$index";
        if(isset($_POST[$temp])) {
           
            $remove_channelid = $channel_ids[$index];
            // remove post from channel table
       
            $query1 = "DELETE FROM Channel WHERE channelID ='$remove_channelid' ";
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
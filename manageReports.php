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
$query = "SELECT * FROM Report";
$result = mysqli_query($db, $query);

echo "<h2>Manage Reports</h2>";
echo "<table>
<tr>
<td><h4>Post ID</h4></td>
<td><h4>Reported by</h4></td>
<td><h4>Comment</h4></td>
</tr>";

$reportCount = 0;
$report_ids = array();
while ($row = mysqli_fetch_array($result)) {
    $id = $row['postID'];
    $post_ids[$reportCount] = $id;
    echo "<tr>";
    echo "<td>". "<input type='text' name='postID' value= ".$row['postID']."></td>";
    echo "<td>". "<input type='text' name='Reportby' value= ".$row['userID']."></td>";
    echo "<td> ".$row['comment']."</td>";;
    echo "<td><a href=\"deleteReport.php?id=".$row['postID']."\">Delete</a></td>";
    $reportCount++;
    echo "</tr>";
}


        
echo "</table>";
echo "</center>";        
header("Refresh:0");
mysqli_close($db);

?>
</div>
<br>
<br>
<br>
<br>
<?php include "includes/adminFooter.html" ?>
</body>
</html>
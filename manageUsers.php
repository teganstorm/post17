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
        
$query = "SELECT * FROM Users";
$result = mysqli_query($db, $query);

echo "<h2>Manage Users</h2>";
echo "<table>
<tr>
<td><h4>User ID</h4></td>
<td><h4>First Name</h4></td>
<td><h4>Last Name</h4></td>
<td><h4>Username</h4></td>
<td><h4>Date of Birth</h4></td>
<td><h4>Email</h4></td>
</tr>";


$user_ids = array();
while ($row = mysqli_fetch_array($result)) {
    $id = $row['userID'];
    $user_ids[$usersCount] = $id;
    echo "<tr>";
    echo "<td>". "<input type='text' name='idtext' value= ".$row['userID']."></td>";
    echo "<td>". "<input type='text' name='fname' value= ".$row['fname']."></td>";
    echo "<td>". " <input type='text' name='lname' value= ".$row['lname']."> </td>";
    echo "<td>". " <input type='text' name='username' value= ".$row['username']."> </td>";
    echo "<td>". " <input type='text' name='dob' value= ".$row['dob']."> </td>";
    echo "<td>". " <input type='text' name='email' value= ".$row['email']."> </td>";
    echo "<td><a href=\"deleteUser.php?id=".$row['userID']."\">Delete</a></td>";
    $userscount++;
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
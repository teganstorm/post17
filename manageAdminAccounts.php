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
$query = "SELECT * FROM admins";
$result = mysqli_query($db, $query);

echo "<h2>Manage Admins</h2>";
echo "<form method=\"post\">
<label> New Admin Username</label>
<input type='text' name='usernamea'>
<label> New Admin password</label>
<input type='password' name='passworda'.>
<input type=\"submit\" style=\"background-color:coral\" value=\"Add Admin\" name=\"newAdmin\"></form>";

if(isset($_POST['newAdmin'])) {
           
        $username = $_POST['usernamea'];
        $password = $_POST['passworda'];
        $queryadmin = "INSERT INTO admins (Ausername,password) VALUES ('$username',md5('$password')) ";
        mysqli_query($db,$queryadmin);
        $username = null;
        $password = null;
        echo '<script>location.href="https://post17.net/manageAdminAccounts.php"</script>';
            }
echo "<table>
<tr>
<td><h4>Admin ID</h4></td>
<td><h4>Admin username</h4></td>
<td><h4>Password</h4></td>
<td><h4></h4></td>
</tr>";

$adminCount = 0;
$admin_ids = array();
while ($row = mysqli_fetch_array($result)) {
    $temp_adminID = $row['adminid'];
    $admin_ids[$adminCount] = $temp_adminID;
    echo "<tr>";
    echo "<td>". "<input type='text' name='adminid' value= ".$row['adminid']."></td>";
    echo "<td>". "<input type='text' name='Ausername' value= ".$row['Ausername']."></td>";
    echo "<td>". "<input type='text' name='password' value= ".$row['password']."></td>";
    echo "<td><form method=\"post\" > <input type=\"submit\" style=\"background-color:coral\" value=\"Update\" name=\"update$adminCount\" onclick=\"return confirm('Are you sure you want to update this challenge?)\"></form>";
    echo "<td><a href=\"deleteAdmin.php?id=".$row['adminid']."\">Delete</a></td>";
    $adminCount++;
    echo "</tr>";
}

    $index = 0;
    for ($index; $index < $adminCount; $index++) {
        $temp = "delete$index";
        if(isset($_POST[$temp])) {
           
            $remove_adminid = $admin_ids[$index];
            // remove post from channel table
       
            $query1 = "DELETE FROM admins WHERE adminid ='$remove_adminid' ";
            mysqli_query($db,$query1);

            }
        
        }
        
    if(isset($_POST1[$post])) {
           
        $query2 = "UPDATE admins SET 
       adminid = '$adminid', 
       username = '$username', 
       password = '$password' WHERE adminid = '$adminid') ";
       mysqli_query($db, $query2);

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
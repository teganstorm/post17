<?php 
  session_start(); 
  header("Cache-Control: max-age=300, must-revalidate"); 

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

<?php include "includes/adminFooter.html" ?>
</body>
</html>
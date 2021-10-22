<?php 
session_start(); 
    if (isset($_POST['back'])){
        array_pop($_SESSION['previous_location']);
        $page = $_SERVER["REQUEST_URI"];
        $_SESSION['page'] = $page;
        $previous_location = end($_SESSION['previous_location']);
    } else {

     $page = $_SERVER["REQUEST_URI"];
  if($_SESSION['page'] != $page){
    $previous_location = "https://".$_SERVER['SERVER_NAME'].$_SESSION['page'];
     $_SESSION['previous_location'][] = $previous_location;
    $_SESSION['page'] = $page;
    $previous_location = end($_SESSION['previous_location']);
  } else {
        $previous_location = end($_SESSION['previous_location']);
  }
    }
echo "<form method=\"post\" style=\"display: inline-block;\" action=\"$previous_location\">
                    <input type=\"submit\" class=\"btn btn-warning\" value=\"back\" name=\"back\" >
                    </form>";
?>
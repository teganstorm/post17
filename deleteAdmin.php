<?php
session_start();
$conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
        }
            
            
                 $delete_adminid = $_GET['id']; // $id is now defined
                 $query0 = "DELETE FROM admins WHERE adminid='$delete_adminid'";
                  mysqli_query($conn, $query0);
                  header("Location: manageAdminAccounts.php");

                  
              
?>
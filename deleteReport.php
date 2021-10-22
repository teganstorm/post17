<?php
session_start();
$conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
        }
            
            
                 $delete_postid = $_GET['id']; // $id is now defined
                 $query0 = "DELETE FROM Report WHERE postID='$delete_postid'";
                  mysqli_query($conn, $query0);

                  // remove post from posts table
                  $query1 = "DELETE FROM Posts WHERE postID='$delete_postid'";
                  mysqli_query($conn, $query1);
                  
                  // remove comments
                  $query2 = "DELETE FROM Comments WHERE postID='$delete_postid'";
                  mysqli_query($conn, $query2);
                  
                  // remove rates
                  $query3 = "DELETE FROM Rating WHERE postID='$delete_postid'";
                  mysqli_query($conn, $query3);
                  
                  // remove tags
                  $query4 = "DELETE FROM Tags WHERE postID='$delete_postid'";
                  mysqli_query($conn, $query4);

                  // remove from channel
                  $query5 = "DELETE FROM postsInChannel WHERE postID='$delete_postid'";
                  mysqli_query($conn, $query5);
                  
                  // refresh page
                  header("Location: manageReports.php");
              
?>
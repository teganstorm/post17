<?php
session_start();
$conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
        }
            
            
                 $delete_challengeid = $_GET['id']; // $id is now defined
                 $removeUsersInChannelsQuery = "DELETE FROM challenge_winners WHERE challengeID = '$delete_challengeid'";
                 mysqli_query($conn,$removeUsersInChannelsQuery);
                 $query1 = "DELETE FROM Participants WHERE challengeID ='$delete_challengeid' ";
                 mysqli_query($conn,$query1);
                 $query2 = "DELETE FROM Posts WHERE challengeID ='$delete_challengeid' ";
                 mysqli_query($conn,$query2);
                 $query3 = "DELETE FROM Challenge WHERE challengeID ='$delete_challengeid' ";
                 mysqli_query($conn,$query3);
                 header("Location: manageChallenges.php");

                  
              
?>
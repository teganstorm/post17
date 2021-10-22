<?php
session_start();
$conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
        }
            
            
                 $delete_channelid = $_GET['id']; // $id is now defined
                 $removeUsersInChannelsQuery = "DELETE FROM usersInChannel WHERE channelID = '$delete_channelid'";
                 mysqli_query($conn,$removeUsersInChannelsQuery);
                 $query1 = "DELETE FROM postsInChannel WHERE channelID ='$delete_channelid' ";
                 mysqli_query($conn,$query1);
                 $query2 = "DELETE FROM Channel WHERE channelID ='$delete_channelid' ";
                 mysqli_query($conn,$query2);
                 header("Location: manageChannels.php");

                  
              
?>
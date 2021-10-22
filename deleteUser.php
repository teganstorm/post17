<?php
session_start();
$conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
        }
           
            $id = $_GET['id'];
            
         
            // get all the challenges created by this user
            $challengeidget = "SELECT * FROM Challenge WHERE userID = '$id'"; 
            $results2 = mysqli_query($conn, $challengeidget);
           
            // need to store all these challengeID's in an array 
            
            $challenge_ids = array();
            $challenge_count = 0;
          

            while ($row = mysqli_fetch_array($results2)) {
                $temp_challengeid = $row['challengeID'];
                $challenge_ids[$challenge_count] = $temp_challengeid;
                $challenge_count++;
              
            }
            
            $index = 0;
            $participantsRemove2 = "DELETE FROM Participants WHERE userID='$id'";
            mysqli_query ($conn,$participantsRemove2);
            // unbind all the posts anyone has to the users challenge 
            for ($index; $index < $challenge_count; $index++) {
                $remove_challengeid = $challenge_ids[$index];
                $query1 = "UPDATE Posts SET challengeID = $null_ WHERE challengeID ='$remove_challengeid' ";
                mysqli_query ($conn,$query1);
                $participantsRemove = "DELETE FROM Participants WHERE challengeID='$remove_challengeid'";
                mysqli_query ($conn,$participantsRemove);
                $challenge_winnersRemove = "DELETE FROM challenge_winners WHERE challengeID = '$remove_challengeid'";
                mysqli_query ($conn,$challenge_winnersRemove);
             
            }
           
            $challenge_winnersRemove = "DELETE FROM challenge_winners WHERE userID = '$id'";
            mysqli_query($conn, $challenge_winnersRemove);
         
    
            // remove all the challenges created by the user
            $challengeRemove = "DELETE FROM Challenge WHERE userID='$id'";
            mysqli_query($conn, $challengeRemove);
           
            // remove all invites sent to the user
            $channelinv_remove = "DELETE FROM channel_invite WHERE userID = '$id'";
            mysqli_query($conn, $channelinv_remove);
           
            
            $channel_ids = array();
            $channel_count = 0;
            
            $get_channels = "SELECT * FROM Channel WHERE userID = '$id'";
            
            $results3 = mysqli_query($conn, $get_channels);
            $cID = mysqli_fetch_assoc($results3);
        
                        
            while ($row = mysqli_fetch_array($results3)) {
                $temp_channelid = $row['channelID'];
                $channel_ids[$channel_count] = $temp_channelid;
                $channel_count++;
            
            }
            $usersInChannelsRemove2 = "DELETE FROM usersInChannel WHERE userID='$id'";
            mysqli_query ($conn,$usersInChannelsRemove2);
            // remove all users in channels created by deleted user.
            // remove invites sent from all channels created from the deleting user
            // remove all the posts in channels created by deleted user.
            for ($index; $index < $channel_count; $index++) {
                $remove_channelid = $channel_ids[$index];
                $usersInChannelsRemove = "DELETE FROM usersInChannel WHERE channelID='$remove_channelid'";
                mysqli_query ($conn,$usersInChannelsRemove);
                $channelinv_remove = "DELETE FROM channel_invite WHERE channelID = '$remove_channelid'";
                mysqli_query ($conn,$channelinv_remove);
                $channelposts_remove = "DELETE FROM postsInChannel WHERE channelID = '$remove_channelid'";
                 mysqli_query ($conn,$channelposts_remove);
           
            }
            
            // remove everyone that the deleted user followed
            $followers_remove = "DELETE FROM Followers WHERE userID = '$id'";
            mysqli_query($conn, $followers_remove);
  
            
            // remove everyone that the deleted user was followed by
            $followers2_remove = "DELETE FROM Followers WHERE followedBy = '$id'";
            mysqli_query($conn, $followers2_remove);
    
            // remove channels created by the deleted user
            $channel_remove = "DELETE FROM Channel WHERE userID = '$id'";
            mysqli_query($conn, $channel_remove);

    
            // remove ratings from all posts created by a user.
            $rating_remove = "DELETE FROM Rating WHERE userID = '$id'";
            mysqli_query($conn, $channel_remove);
         
            
            // remove all the ratings from posts that the deleted user created
            $post_ids = array();
            $post_count = 0;
            $index = 0;
            
            $get_posts = "SELECT * FROM Posts WHERE userID = '$id'";
            
            $results4 = mysqli_query($conn, $get_posts);
       
            
            while ($row = mysqli_fetch_array($results4)) {
                $temp_postID = $row['postID'];
                $post_ids[$post_count] = $temp_postID;
                $post_count++;
             
            }
            
            // remove all the ratings on any posts the deleted user may have posted
            // remove all the reports made on any of the deleted users posts
            // remove all the savedposts the deleted user created
            // remove any tags from posts the deleted user created
            // 
            $savedPosts2 = "DELETE FROM savedPosts WHERE userID = '$id'";
            mysqli_query($conn, $savedPosts2);
            for ($index; $index < $post_count; $index++) {
                $remove_postID = $post_ids[$index];
                $removeRatingQuery = "DELETE FROM Rating WHERE postID = '$remove_postID'";
                mysqli_query($conn, $removeRatingQuery);
                $reportQuery = "DELETE FROM Report WHERE postID = '$remove_postID'";
                mysqli_query($conn, $reportQuery); 
                $savedPosts = "DELETE FROM savedPosts WHERE postID = '$remove_postID'";
                mysqli_query($conn, $savedPosts);
                $removeTags = "DELETE FROM Tags WHERE postID = '$remove_postID'";
                mysqli_query($conn, $removeTags);
             
            }
            
            // remove any ratings the user gave to anyone else
            $removeRatingUserQuery = "DELETE FROM Rating WHERE userID = '$id'";
            
            mysqli_query($conn, $removeRatingUserQuery);
            
            // remove any reports the deleted user may have made
            $reportQuery = "DELETE FROM Report WHERE userID = '$id'";
            mysqli_query($conn, $reportQuery);

            
            // remove all comments posted by the user
            $comment_remove = "DELETE FROM Comments WHERE userID = '$id'";
            mysqli_query($conn, $comment_remove);

            
            $index = 0;
            for ($index; $index < $post_count; $index++) {
                $remove_postID = $post_ids[$index];
                $removePost = "DELETE FROM Posts WHERE postID = '$remove_postID'";
                mysqli_query($conn, $removePost);
            }
            
 
          
            // delete user from database
            $sql = "DELETE FROM Users WHERE userID='$id'";
            mysqli_query($conn, $sql);
         
        
 	
 	      echo '<script>location.href="https://post17.net/manageUsers.php"</script>';
?>
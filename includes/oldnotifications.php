<!-- Find challenge winner -->
<?php include "findchallengewinner.php" ?>
    
    
    <!-- Notifications for large screens -->
    <div class = "col-sm-4 d-none d-sm-block">
    <div class=" text-center border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body">
    <?php
        
        // ----------NOTIFICATIONS----------
        echo "<h2>Notifications</h2>";
        echo "Set your notifications as seen to remove them from your notifications wall.";
        $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        echo "<br>";
        echo "<hr>";
        echo "<br>";
        $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
        }
        $username = $_SESSION['username'];
        
        
          $query_userid = "SELECT * FROM Users WHERE username='$username'";
          $result_users = mysqli_query($conn, $query_userid);
          $user = mysqli_fetch_assoc($result_users);
          $id = $user['userID'];
          $notif = $user['notifications'];
          
          if ($notif == 1) {
          
            // make the array to store the post_id's
            $notifications_post_ids = array();
            $notifications_post_ids_index = 0;
            $userids = array();
            $userids_index = 0;
            
            $query = "SELECT * FROM Posts WHERE userID='$id'";
            $find_post_ids = mysqli_query($conn, $query);
            // loop through the posts and add them to the array
            while ($post = mysqli_fetch_array($find_post_ids)) {
                $temp_postid = $post['postID'];
                $notifications_post_ids[$notifications_post_ids_index] = $temp_postid;
                $notifications_post_ids_index++;
            }
            
            // -----find new followers-----
            // option to view user account and mark notification as seen
            echo "<h5>New followers: </h5>";
            $followers_array = array();
            $followers_array_index = 0;
            $query_new_followers = "SELECT * FROM Followers WHERE userID='$id' AND seen=0";
            $find_followers = mysqli_query($conn, $query_new_followers);
            while ($follower = mysqli_fetch_array($find_followers)) {
                $follower_userid = $follower['followedBy'];
                $followers_array[$followers_array_index] = $follower_userid;
                
                // get the username 
                $q = "SELECT username FROM Users WHERE userID='$follower_userid'";
                $result = mysqli_query($conn, $q);
                $user = mysqli_fetch_assoc($result);
                // print the new follower message
                if ($user) {
                    $printusername = $user['username'];
                    echo "@$printusername has started following you.<br>
                            <form method=\"post\" action='youraccount.php?view_username=$printusername'>
                                <input type=\"submit\" value=\"View @$printusername's account\" name=\"view_account$current_index\">
                            </form>
                            <form method=\"post\">
                                <input type=\"submit\" value=\"Seen\" name=\"usernameseen$followers_array_index\">
                            </form><br>
                    ";
                    // view their account
                    // set notification as seen
                } else {
                    echo "this is a error message<br>";
                }
                
                $followers_array_index++;
            }
            // new followers requests
            $queryfollowreq = "SELECT * FROM request_follow WHERE privateuserID='$id'";
            $result = mysqli_query($conn, $queryfollowreq);
            $followers_request_index = 0;
            $followers_request = array();
            while ($request = mysqli_fetch_array($result)) {
                $requestUserID = $request['wanttofollowuserID'];
                $followers_request[$followers_request_index] = $requestUserID;
                $followers_request_index++;
                
                // get the username
                $q = "SELECT * FROM Users WHERE userID='$requestUserID'";
                $r = mysqli_query($conn, $q);
                $user = mysqli_fetch_assoc($r);
                $printusername = $user['username'];
                
                echo "@$printusername has requested to follow you.";
                    echo "<form method=\"post\" action='youraccount.php?view_username=$printusername'>
                                <input type=\"submit\" value=\"View @$printusername's account\" name=\"view_account$current_index\">
                            </form>
                            <form method=\"post\">
                                <input type=\"submit\" value=\"Accept\" name=\"accept$requestUserID\">
                                <input type=\"submit\" value=\"Decline\" name=\"decline$requestUserID\">
                            </form><br>
                    ";
                
            }
            if (($followers_array_index == 0)&&($followers_request_index==0)) {
                echo "No new followers.<br>";
            }
            
            // -----find new likes-----
            // option to view post and mark notification as seen
            echo "<br>";
            echo "<hr>";
            echo "<br>";
            echo "<h5>New likes: </h5>";
            $likes = 0;
            $likes_array = array();
            $likes_array_index = 0;
            for ($i=0; $i<=$notifications_post_ids_index; $i++) {
                $post_id = $notifications_post_ids[$i];
                $query_newlikes = "SELECT * FROM Rating WHERE postID='$post_id' AND seen=0";
                $find_newlikes = mysqli_query($conn, $query_newlikes);
                while ($post = mysqli_fetch_array($find_newlikes)) {
                    $likes ++;
                    $temp_array = array();
                    $temp_array[0] = $post_id;
                    $userid = $post['userID'];
                    $temp_array[1] = $userid;
                    $likes_array[$likes_array_index] = $temp_array;
                    $likes_array_index++;
                    
                    $userid = $post['userID'];
                    $userids[$userids_index] = $userid;
                    $qusername = "SELECT username FROM Users WHERE userID='$userid'";
                    $result = mysqli_query($conn, $qusername);
                    $user = mysqli_fetch_assoc($result);
                    if ($user) {
                        $printusername = $user['username'];
                        $name = "$userid\seenlike$post_id"; 
                        echo "@$printusername has liked your post.<br>
                                <form method=\"post\" action='viewpost.php?post=$post_id'>
                                    <input type=\"submit\" value=\"View post\" name=\"viewpost$i\">
                                </form>
                                <form method=\"post\">
                                    <input type=\"submit\" value=\"Seen\" name=\"$name\">
                                </form><br>
                        ";
                    }
                    $userids_index++;
                }
            } 
            if ($likes == 0) {
                echo "No new likes.<br>";
            }
            
            // -----find new comments-----
            // option to view post and mark notification as seen
            echo "<br>";
            echo "<hr>";
            echo "<br>";
            echo "<h5>New comments: </h5>";
            $comments = 0;
            $comments_array = array();
            $comments_array_index = 0;
            for ($i=0; $i<=$notifications_post_ids_index; $i++) {
                $post_id = $notifications_post_ids[$i];
                $query_newcomments = "SELECT * FROM Comments WHERE postID='$post_id' AND seen=0";
                $find_newcomments = mysqli_query($conn, $query_newcomments);
                $comments_on_this_post = 0;
                while ($post = mysqli_fetch_array($find_newcomments)) {
                    $comments ++;
                    $comment = $post['comment'];
                    $userid = $post['userID'];
                    $qusername = "SELECT username FROM Users WHERE userID='$userid'";
                    $result = mysqli_query($conn, $qusername);
                    $user = mysqli_fetch_assoc($result);
                    
                    // make an array with the userID, the comment, and the post id and put the aray in the comments aray at index i
                    $temp_array = array();
                    // 0 = postID
                    $temp_array[0] = $post_id;
                    // 1 = userID
                    $temp_array[1] = $userid;
                    // 2 = the comment
                    $temp_array[2] = $comment;
                    // 3 = $comments_on_this_post
                    $temp_array[3] = $comments_on_this_post;
                    
                    $comments_array[$comments_array_index] = $temp_array;
                    $comments_array_index++;
                    
                    if ($user) {
                        $printusername = $user['username'];
                        $name = "$post_id\commentseen$userid\and$comments_on_this_post";
                        echo "@$printusername commented \"$comment\" on your post.<br>
                                <form method=\"post\" action='viewpost.php?post=$post_id'>
                                    <input type=\"submit\" value=\"View post\" name=\"viewpost$i\">
                                </form>
                                <form method=\"post\">
                                    <input type=\"submit\" value=\"Seen\" name=\"$name\">
                                </form><br>
                        ";
                    }
                    $comments_on_this_post++;
                }
            } 
            if ($comments == 0) {
                echo "No new comments.<br>";
            }
            
            // -----find any challenges won-----
            echo "<br>";
            echo "<hr>";
            echo "<br>";
            echo "<h5>Challenge updates: </h5>";
            $wins = 0;
            $wins_array = array();
            $wins_array_index = 0;
            
            $query_challenge = "SELECT * FROM challenge_winners WHERE userID='$id' AND seen=0";
            $find_challenge = mysqli_query($conn, $query_challenge);
            while ($challenge = mysqli_fetch_array($find_challenge)) {
                    $wins ++;
                    $challenge_id = $challenge['challengeID'];
                    $wins_array[$wins_array_index] = $challenge_id;
                    
                    
                    $qchallengename = "SELECT * FROM Challenge WHERE challengeID='$challenge_id'";
                    $result = mysqli_query($conn, $qchallengename);
                    $challenge = mysqli_fetch_assoc($result);
                    $challenge_name = $challenge['challengeName'];
                    
                        echo "Congratulations! You have won the challenge: $challenge_name<br>
                                <form method=\"post\" action='viewchallenge.php?challenge=$challenge_id'>
                                    <input type=\"submit\" value=\"View Challenge\" name=\"viewchallenge\">
                                </form>
                                <form method=\"post\">
                                    <input type=\"submit\" value=\"Seen\" name=\"challengewin$wins_array_index\">
                                </form><br>";
                    $wins_array_index++;
            }
            if ($wins == 0) {
                echo "No new challenge updates.<br>";
            }
            
            // -----find any channel updates-----
            echo "<br>";
            echo "<hr>";
            echo "<br>";
            echo "<h5>Channel updates: </h5>";
            $channel_updates = array();
            $channel_updates_index = 0;
            $query_channel_invite = "SELECT * FROM channel_invite WHERE userID='$id'";
            $res = mysqli_query($conn, $query_channel_invite);
            while ($invite = mysqli_fetch_array($res)) {
                $channelid = $invite['channelID'];
                $channel_updates[$channel_updates_index] = $channelid;
                $channel_updates_index++;
                
                // get the channel info
                $q = "SELECT * FROM Channel WHERE channelID='$channelid'";
                $r = mysqli_query($conn, $q);
                $channel = mysqli_fetch_assoc($r);
                $channelname = $channel['channelName'];
                
                
                        echo "You have been invited to join the channel: $channelname<br>
                                <form method=\"post\" action='viewchannel.php?channel=$channelid'>
                                    <input type=\"submit\" value=\"View Channel\" name=\"viewchannel\">
                                </form>
                                <form method=\"post\">
                                    <input type=\"submit\" value=\"Accept and Join\" name=\"join$channelid\">
                                    <input type=\"submit\" value=\"Ignore and Remove\" name=\"ignore$channelid\">
                                </form><br>";
                
            }
            if ($channel_updates_index == 0) {
                echo "No new channel updates.<br>";
            }
            
            
            
            echo "<br>";
            
            
            // check followers seen buttons
            for ($i=0; $i<=$followers_array_index; $i++) {
                $temp = "usernameseen$i";
                if(isset($_POST[$temp])) {
                    $followedBy = $followers_array[$i];
                    // already know $id is my id
                    $q = "UPDATE Followers SET seen=1 WHERE userID='$id' AND followedBy='$followedBy'";
                    mysqli_query($db, $q);
                    
                    unset($_POST[$temp]);
                    echo("<meta http-equiv='refresh' content='1'>");
                }
            }
            
            // check followers request accept and decline buttons
            for ($i=0; $i<=$followers_request_index; $i++) {
                $t = $followers_request[$i];
                $temp = "accept$t";
                if(isset($_POST[$temp])) {
                    // already know $id is my id
                    // remove from requests table
                    $q = "DELETE FROM request_follow WHERE wanttofollowuserID='$t' AND privateuserID='$id'";
                    mysqli_query($db, $q);
                    
                    // add to followers table
                    $queryfollow = "INSERT INTO Followers (userID, followedBy) VALUES ('$id','$t')";
                    mysqli_query($db, $queryfollow);
                    
                    unset($_POST[$temp]);
                    echo("<meta http-equiv='refresh' content='1'>");
                }
                $temp = "decline$t";
                if(isset($_POST[$temp])) {
                    // already know $id is my id
                    // remove from requests table
                    $q = "DELETE FROM request_follow WHERE wanttofollowuserID='$t' AND privateuserID='$id'";
                    mysqli_query($db, $q);
                    
                    unset($_POST[$temp]);
                    echo("<meta http-equiv='refresh' content='1'>");
                }
            }
            
            // check like seen buttons
            // for each post in array
            for ($i=0; $i<$likes_array_index; $i++) {
                $temp_array = $likes_array[$i];
                $updateuserid = $temp_array[1];
                $updatepostid = $temp_array[0];
                $temp = "$updateuserid\seenlike$updatepostid";
                if(isset($_POST[$temp])) {
                    // set like to seen
                    $q = "UPDATE Rating SET seen=1 WHERE userID='$updateuserid' AND postID='$updatepostid'";
                    mysqli_query($db, $q);
                    unset($_POST[$temp]);
                    echo("<meta http-equiv='refresh' content='1'>");
                }
            }
                // check comment seen buttons
                // echo post in post array
                // for each comment array in the comments array
            for ($j=0; $j<$comments_array_index; $j++) {
                $temparray = $comments_array[$j];
                $post_id = $temparray[0];
                $userid = $temparray[1];
                $comment = $temparray[2];
                $comments_on_this_post = $temparray[3];
                $temp = "$post_id\commentseen$userid\and$comments_on_this_post"; 
                if(isset($_POST[$temp])) {
                    $q = "UPDATE Comments SET seen=1 WHERE userID='$userid' AND postID='$post_id' AND comment='$comment'";
                    mysqli_query($db, $q);
                    unset($_POST[$temp]);
                    echo("<meta http-equiv='refresh' content='1'>");
                }
            }
            
            // check challenges seen button
            for ($j=0; $j<$wins_array_index; $j++) {
                $temp = "challengewin$j"; 
                if(isset($_POST[$temp])) {
                    $challenge_id = $wins_array[$j];
                    $q = "UPDATE challenge_winners SET seen=1 WHERE userID='$id' AND challengeID='$challenge_id'";
                    mysqli_query($db, $q);
                    unset($_POST[$temp]);
                    echo("<meta http-equiv='refresh' content='1'>");
                }
            }
            
            // check channel updates buttons accept and ignore
            for ($j=0; $j<$channel_updates_index; $j++) {
                $channelid = $channel_updates[$j];
                $temp = "join$channelid"; 
                if(isset($_POST[$temp])) {
                    
                    $sql100 = "INSERT INTO usersInChannel (channelID, userID) VALUES ('$channelid' , '$id')";
                    mysqli_query($db, $sql100);
                    
                    $sql101 = "DELETE FROM channel_invite WHERE channelID='$channelid' AND userID='$id'";
                    mysqli_query($db, $sql101);
                    
                    unset($_POST[$temp]);
                    echo("<meta http-equiv='refresh' content='1'>");
                }
                $temp = "ignore$channelid"; 
                if(isset($_POST[$temp])) {
                    
                    $sql101 = "DELETE FROM channel_invite WHERE channelID='$channelid' AND userID='$id'";
                    mysqli_query($db, $sql101);
                    
                    unset($_POST[$temp]);
                    echo("<meta http-equiv='refresh' content='1'>");
                }
                
            }
            
            
            
          } else {
              echo "<br><br>";
              echo "You have turned your notifications off.<br>";
              echo "Access account settings from the My Account page to tun them back on.<br>";
          }
        
    ?>
        
    </div>
    </div>
    
    <!-- The notifications section for mobile/small screens -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title" id="staticBackdropLabel">Notifications</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <a>Set your notifications as seen to remove them from your notifications wall.</a>
              <hr>
              <br>
            <?php
        
                        // ----------NOTIFICATIONS----------
                        $conn = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                        $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                        if ($conn->connect_error) {
                                  die("Connection failed: " . $conn->connect_error);
                        }
                        $username = $_SESSION['username'];
                        
                        
                          $query_userid = "SELECT * FROM Users WHERE username='$username'";
                          $result_users = mysqli_query($conn, $query_userid);
                          $user = mysqli_fetch_assoc($result_users);
                          $id = $user['userID'];
                          
                        $notif = $user['notifications'];
          
                        if ($notif == 1) {
                          
                            // make the array to store the post_id's
                            $notifications_post_ids = array();
                            $notifications_post_ids_index = 0;
                            $userids = array();
                            $userids_index = 0;
                            
                            $query = "SELECT * FROM Posts WHERE userID='$id'";
                            $find_post_ids = mysqli_query($conn, $query);
                            // loop through the posts and add them to the array
                            while ($post = mysqli_fetch_array($find_post_ids)) {
                                $temp_postid = $post['postID'];
                                $notifications_post_ids[$notifications_post_ids_index] = $temp_postid;
                                $notifications_post_ids_index++;
                            }
                            
                            // -----find new followers-----
                            // option to view user account and mark notification as seen
                            echo "<h5>New followers: </h5>";
                            $followers_array = array();
                            $followers_array_index = 0;
                            $query_new_followers = "SELECT * FROM Followers WHERE userID='$id' AND seen=0";
                            $find_followers = mysqli_query($conn, $query_new_followers);
                            while ($follower = mysqli_fetch_array($find_followers)) {
                                $follower_userid = $follower['followedBy'];
                                $followers_array[$followers_array_index] = $follower_userid;
                                
                                // get the username 
                                $q = "SELECT username FROM Users WHERE userID='$follower_userid'";
                                $result = mysqli_query($conn, $q);
                                $user = mysqli_fetch_assoc($result);
                                // print the new follower message
                                if ($user) {
                                    $printusername = $user['username'];
                                    echo "@$printusername has started following you.<br>
                                            <form method=\"post\" action='youraccount.php?view_username=$printusername'>
                                                <input type=\"submit\" value=\"View @$printusername's account\" name=\"view_account$current_index\">
                                            </form>
                                            <form method=\"post\">
                                                <input type=\"submit\" value=\"Seen\" name=\"usernameseen$followers_array_index\">
                                            </form><br>
                                    ";
                                    // view their account
                                    // set notification as seen
                                } else {
                                    echo "this is a error message<br>";
                                }
                                
                                $followers_array_index++;
                            }
                            // new followers requests
                            $queryfollowreq = "SELECT * FROM request_follow WHERE privateuserID='$id'";
                            $result = mysqli_query($conn, $queryfollowreq);
                            $followers_request_index = 0;
                            $followers_request = array();
                            while ($request = mysqli_fetch_array($result)) {
                                $requestUserID = $request['wanttofollowuserID'];
                                $followers_request[$followers_request_index] = $requestUserID;
                                $followers_request_index++;
                                
                                // get the username
                                $q = "SELECT * FROM Users WHERE userID='$requestUserID'";
                                $r = mysqli_query($conn, $q);
                                $user = mysqli_fetch_assoc($r);
                                $printusername = $user['username'];
                                
                                echo "@$printusername has requested to follow you.";
                                    echo "<form method=\"post\" action='youraccount.php?view_username=$printusername'>
                                                <input type=\"submit\" value=\"View @$printusername's account\" name=\"view_account$current_index\">
                                            </form>
                                            <form method=\"post\">
                                                <input type=\"submit\" value=\"Accept\" name=\"accept$requestUserID\">
                                                <input type=\"submit\" value=\"Decline\" name=\"decline$requestUserID\">
                                            </form><br>
                                    ";
                                
                            }
                            if (($followers_array_index == 0)&&($followers_request_index==0)) {
                                echo "No new followers.<br>";
                            }
                            
                            // -----find new likes-----
                            // option to view post and mark notification as seen
                            echo "<br>";
            echo "<hr>";
            echo "<br>";
                            echo "<h5>New likes: </h5>";
                            $likes = 0;
                            $likes_array = array();
                            $likes_array_index = 0;
                            for ($i=0; $i<=$notifications_post_ids_index; $i++) {
                                $post_id = $notifications_post_ids[$i];
                                $query_newlikes = "SELECT * FROM Rating WHERE postID='$post_id' AND seen=0";
                                $find_newlikes = mysqli_query($conn, $query_newlikes);
                                while ($post = mysqli_fetch_array($find_newlikes)) {
                                    $likes ++;
                                    $temp_array = array();
                                    $temp_array[0] = $post_id;
                                    $userid = $post['userID'];
                                    $temp_array[1] = $userid;
                                    $likes_array[$likes_array_index] = $temp_array;
                                    $likes_array_index++;
                                    
                                    $userid = $post['userID'];
                                    $userids[$userids_index] = $userid;
                                    $qusername = "SELECT username FROM Users WHERE userID='$userid'";
                                    $result = mysqli_query($conn, $qusername);
                                    $user = mysqli_fetch_assoc($result);
                                    if ($user) {
                                        $printusername = $user['username'];
                                        $name = "$userid\seenlike$post_id"; 
                                        echo "@$printusername has liked your post.<br>
                                                <form method=\"post\" action='viewpost.php?post=$post_id'>
                                                    <input type=\"submit\" value=\"View post\" name=\"viewpost$i\">
                                                </form>
                                                <form method=\"post\">
                                                    <input type=\"submit\" value=\"Seen\" name=\"$name\">
                                                </form><br>
                                        ";
                                    }
                                    $userids_index++;
                                }
                            } 
                            if ($likes == 0) {
                                echo "No new likes.<br>";
                            }
                            echo "<br>";
                            // -----find new comments-----
                            // option to view post and mark notification as seen
            echo "<hr>";
            echo "<br>";
                            echo "<h5>New comments: </h5>";
                            $comments = 0;
                            $comments_array = array();
                            $comments_array_index = 0;
                            for ($i=0; $i<=$notifications_post_ids_index; $i++) {
                                $post_id = $notifications_post_ids[$i];
                                $query_newcomments = "SELECT * FROM Comments WHERE postID='$post_id' AND seen=0";
                                $find_newcomments = mysqli_query($conn, $query_newcomments);
                                $comments_on_this_post = 0;
                                while ($post = mysqli_fetch_array($find_newcomments)) {
                                    $comments ++;
                                    $comment = $post['comment'];
                                    $userid = $post['userID'];
                                    $qusername = "SELECT username FROM Users WHERE userID='$userid'";
                                    $result = mysqli_query($conn, $qusername);
                                    $user = mysqli_fetch_assoc($result);
                                    
                                    // make an array with the userID, the comment, and the post id and put the aray in the comments aray at index i
                                    $temp_array = array();
                                    // 0 = postID
                                    $temp_array[0] = $post_id;
                                    // 1 = userID
                                    $temp_array[1] = $userid;
                                    // 2 = the comment
                                    $temp_array[2] = $comment;
                                    // 3 = $comments_on_this_post
                                    $temp_array[3] = $comments_on_this_post;
                                    
                                    $comments_array[$comments_array_index] = $temp_array;
                                    $comments_array_index++;
                                    
                                    if ($user) {
                                        $printusername = $user['username'];
                                        $name = "$post_id\commentseen$userid\and$comments_on_this_post";
                                        echo "@$printusername commented \"$comment\" on your post.<br>
                                                <form method=\"post\" action='viewpost.php?post=$post_id'>
                                                    <input type=\"submit\" value=\"View post\" name=\"viewpost$i\">
                                                </form>
                                                <form method=\"post\">
                                                    <input type=\"submit\" value=\"Seen\" name=\"$name\">
                                                </form><br>
                                        ";
                                    }
                                    $comments_on_this_post++;
                                }
                            } 
                            if ($comments == 0) {
                                echo "No new comments.<br>";
                            }
                            echo "<br>";
                            // -----find any challenges won-----
            echo "<hr>";
            echo "<br>";
                            echo "<h5>Challenge updates: </h5>";
                            $wins = 0;
                            $wins_array = array();
                            $wins_array_index = 0;
                            
                            $query_challenge = "SELECT * FROM challenge_winners WHERE userID='$id' AND seen=0";
                            $find_challenge = mysqli_query($conn, $query_challenge);
                            while ($challenge = mysqli_fetch_array($find_challenge)) {
                                    $wins ++;
                                    $challenge_id = $challenge['challengeID'];
                                    $wins_array[$wins_array_index] = $challenge_id;
                                    
                                    
                                    $qchallengename = "SELECT * FROM Challenge WHERE challengeID='$challenge_id'";
                                    $result = mysqli_query($conn, $qchallengename);
                                    $challenge = mysqli_fetch_assoc($result);
                                    $challenge_name = $challenge['challengeName'];
                                    
                                        echo "Congratulations! You have won the challenge: $challenge_name<br>
                                                <form method=\"post\" action='viewchallenge.php?challenge=$challenge_id'>
                                                    <input type=\"submit\" value=\"View Challenge\" name=\"viewchallenge\">
                                                </form>
                                                <form method=\"post\">
                                                    <input type=\"submit\" value=\"Seen\" name=\"challengewin$wins_array_index\">
                                                </form><br>";
                                    $wins_array_index++;
                            }
                            if ($wins == 0) {
                                echo "No new challenge updates.<br>";
                            }
                            
                            // -----find any channel updates-----
                            echo "<br>";
                            echo "<hr>";
                            echo "<br>";
                            echo "<h5>Channel updates: </h5>";
                            $channel_updates = array();
                            $channel_updates_index = 0;
                            $query_channel_invite = "SELECT * FROM channel_invite WHERE userID='$id'";
                            $res = mysqli_query($conn, $query_channel_invite);
                            while ($invite = mysqli_fetch_array($res)) {
                                $channelid = $invite['channelID'];
                                $channel_updates[$channel_updates_index] = $channelid;
                                $channel_updates_index++;
                                
                                // get the channel info
                                $q = "SELECT * FROM Channel WHERE channelID='$channelid'";
                                $r = mysqli_query($conn, $q);
                                $channel = mysqli_fetch_assoc($r);
                                $channelname = $channel['channelName'];
                                
                                
                                        echo "You have been invited to join the channel: $channelname<br>
                                                <form method=\"post\" action='viewchannel.php?channel=$channelid'>
                                                    <input type=\"submit\" value=\"View Channel\" name=\"viewchannel\">
                                                </form>
                                                <form method=\"post\">
                                                    <input type=\"submit\" value=\"Accept and Join\" name=\"join$channelid\">
                                                    <input type=\"submit\" value=\"Ignore and Remove\" name=\"ignore$channelid\">
                                                </form><br>";
                                
                            }
                            if ($channel_updates_index == 0) {
                                echo "No new channel updates.<br>";
                            }
                            
                            
                            // check followers seen buttons
                            for ($i=0; $i<=$followers_array_index; $i++) {
                                $temp = "usernameseen$i";
                                if(isset($_POST[$temp])) {
                                    $followedBy = $followers_array[$i];
                                    // already know $id is my id
                                    $q = "UPDATE Followers SET seen=1 WHERE userID='$id' AND followedBy='$followedBy'";
                                    mysqli_query($db, $q);
                                    
                                    unset($_POST[$temp]);
                                    echo("<meta http-equiv='refresh' content='1'>");
                                }
                            }
                            // check like seen buttons
                            // for each post in array
                            for ($i=0; $i<$likes_array_index; $i++) {
                                $temp_array = $likes_array[$i];
                                $updateuserid = $temp_array[1];
                                $updatepostid = $temp_array[0];
                                $temp = "$updateuserid\seenlike$updatepostid";
                                if(isset($_POST[$temp])) {
                                    // set like to seen
                                    $q = "UPDATE Rating SET seen=1 WHERE userID='$updateuserid' AND postID='$updatepostid'";
                                    mysqli_query($db, $q);
                                    unset($_POST[$temp]);
                                    echo("<meta http-equiv='refresh' content='1'>");
                                }
                            }
                                // check comment seen buttons
                                // echo post in post array
                                // for each comment array in the comments array
                            for ($j=0; $j<$comments_array_index; $j++) {
                                $temparray = $comments_array[$j];
                                $post_id = $temparray[0];
                                $userid = $temparray[1];
                                $comment = $temparray[2];
                                $comments_on_this_post = $temparray[3];
                                $temp = "$post_id\commentseen$userid\and$comments_on_this_post"; 
                                if(isset($_POST[$temp])) {
                                    $q = "UPDATE Comments SET seen=1 WHERE userID='$userid' AND postID='$post_id' AND comment='$comment'";
                                    mysqli_query($db, $q);
                                    unset($_POST[$temp]);
                                    echo("<meta http-equiv='refresh' content='1'>");
                                }
                            }
                            
                            // check challenges seen button
                            for ($j=0; $j<$wins_array_index; $j++) {
                                $temp = "challengewin$j"; 
                                if(isset($_POST[$temp])) {
                                    $challenge_id = $wins_array[$j];
                                    $q = "UPDATE challenge_winners SET seen=1 WHERE userID='$id' AND challengeID='$challenge_id'";
                                    mysqli_query($db, $q);
                                    unset($_POST[$temp]);
                                    echo("<meta http-equiv='refresh' content='1'>");
                                }
                            }
                            
                            // check channel updates buttons accept and ignore
                        for ($j=0; $j<$channel_updates_index; $j++) {
                            $channelid = $channel_updates[$j];
                            $temp = "join$channelid"; 
                            if(isset($_POST[$temp])) {
                                
                                $sql100 = "INSERT INTO usersInChannel (channelID, userID) VALUES ('$channelid' , '$id')";
                                mysqli_query($db, $sql100);
                                
                                $sql101 = "DELETE FROM channel_invite WHERE channelID='$channelid' AND userID='$id'";
                                mysqli_query($db, $sql101);
                                
                                unset($_POST[$temp]);
                                echo("<meta http-equiv='refresh' content='1'>");
                            }
                            $temp = "ignore$channelid"; 
                            if(isset($_POST[$temp])) {
                                
                                $sql101 = "DELETE FROM channel_invite WHERE channelID='$channelid' AND userID='$id'";
                                mysqli_query($db, $sql101);
                                
                                unset($_POST[$temp]);
                                echo("<meta http-equiv='refresh' content='1'>");
                            }
                            
                        }
                        } else {
                              echo "<br><br>";
                              echo "You have turned your notifications off.<br>";
                              echo "Access account settings from the My Account page to tun them back on.<br>";
                        }
                        
                    ?>
                    <br>
                 </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
        </div>
      </div>
    </div>
     <?php

        $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
        
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        
        $challenge_data = "SELECT * FROM Challenge" ;
        $results = mysqli_query($db, $challenge_data);
        $challenge_ids = array();
        $index = 0;
        $challenge_index = 0;

        
        $currDate1 = date("Y-m-d"); //today's date
        $currDate1 = new DateTime($currDate1); // convert into seconds
        
        // counts how many challenges there are in challenge index
        // also stores all the challenge ID's in an array.
        while ($row = mysqli_fetch_array($results)) {
            $temp_CID = $row['challengeID'];
            $challenge_ids[$challenge_index] = $temp_CID;
            $challenge_index++;
        }
        
        $num_challenges = $challenge_index;
            
        while ($index < $num_challenges) {
            
            $tmp_id = $challenge_ids[$index];

            $tmp_challenge_info_query = "SELECT * FROM Challenge WHERE challengeID = '$tmp_id'";
            
            $results2 = mysqli_query($db, $tmp_challenge_info_query);
            $tmp_challenge_info = mysqli_fetch_assoc($results2);
            $tmp_challenge_start = ($tmp_challenge_info['startDate']);
            $tmp_challenge_end = ($tmp_challenge_info['endDate']);
            $tmp_challenge_userID = $tmp_challenge_info['userID'];
            $tmp_challenge_username_query = "SELECT * FROM Users WHERE userID = '$tmp_challenge_userID'";
            $results3 = mysqli_query($db,  $tmp_challenge_username_query);
            $tmp_challenge_user_info = mysqli_fetch_assoc($results3);
            $tmp_challenge_username = $tmp_challenge_user_info['username'];

            $currDate1 = date("Y-m-d"); 
            $currDate1 = new DateTime($currDate1); // convert into seconds
            $startdate1 = new DateTime($tmp_challenge_start); // convert the start date specified by the user into seconds
            $enddate1 = new DateTime($tmp_challenge_end); // convert the start date specified by the user into seconds*/
            
            if ($currDate1 >= $enddate1){
                
                // check if the winner has already been set
                $checkwinnerset = $tmp_challenge_info['winnerUserID'];
                if (!$checkwinnerset) {
                    // winner is not set yet so now find the post with most likes
                    $q = "SELECT * FROM Posts WHERE challengeID='$tmp_id' ORDER BY likes DESC";
                    $r = mysqli_query($db, $q);
                    
                    $max_likes = 0;
                    while ($p = mysqli_fetch_array($r)) {
                        // we ordered by likes biggest first in the query so at least the first result is a winner
                        $temp_likes = $p['likes'];
                        if ($temp_likes >= $max_likes) {
                            $max_likes = $temp_likes;
                            // 0 = userID
                            $uid = $p['userID'];
                            // 1 = postid
                            $pid = $p['postID'];
                            
                            $q_winner = "INSERT INTO challenge_winners (userID, challengeID, postID, seen) VALUES ('$uid', '$tmp_id', '$pid', '0')";
                            mysqli_query($db, $q_winner);
                            $update_challenge_set_winner = "UPDATE Challenge set winnerUserID='$uid' WHERE challengeID='$tmp_id'";
                            mysqli_query($db, $update_challenge_set_winner);
                        }
                    }
                    $qdelete = "DELETE FROM Participants WHERE challengeID='$tmp_id'";
                    mysqli_query($db, $qdelete);
                }
            }
            $index++;
        }
        
    ?>
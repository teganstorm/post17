<?php 
  session_start(); 

if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
  
  
?>

<?php
    $errors = array();
    if (isset($_POST['uploadBtn']) && ($_POST['uploadBtn'] == 'Create Post')) {
        // get details of the uploaded file
        $file = $_FILES['uploadedFile']['tmp_name'];
        $fileName = $_FILES['uploadedFile']['name'];
        $uploadFileDir = 'post-pictures/';
        $fileSize = $_FILES['uploadedFile']['size'];
        $fileType = $_FILES['uploadedFile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        if (!isset($file)){
            echo "You did not choose an image file.<br>";
            echo "You must choose an image file to create a post.<br>";
        //} else if ($fileSize > 1080) {
           // echo "The file you choose is too large.<br>";
            //echo "You must choose a smaller sized image file.<br>";
        } else {
     
            // sanitize file-name
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
         
            // check if file has one of the following extensions
            $allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc', 'webp');
        
            if ($newFileName != "") {
                
                if (in_array($fileExtension, $allowedfileExtensions))
                {
                  // directory in which the uploaded file will be moved
                  $uploadFileDir = 'post-pictures/';
                  $dest_path = $uploadFileDir . $newFileName;
                  
                     
                 
                      if(move_uploaded_file($file, $dest_path)) 
                      {
                        $message ='File is successfully uploaded.';
                      }
                      else
                      {
                        $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                      }
                    }
                    
                    $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                    
                    if ($db->connect_error) {
                              die("Connection failed: " . $db->connect_error);
                    }
                
                    $username = $_SESSION['username'];
                    if (empty($username)) {
                      	    echo "<br><br><br><br><br>help";
                        }
                    $caption = ($_POST['caption']);
                    $geotag = ($_POST['geotag']);
                    $tags_string = ($_POST['tags']);
                    $tags_array = explode(" ", $tags_string);
                    
                    $date = date("Y/m/d");
                    $time = date("H.i.s");
                    $datetime = "$date $time";
                    
                    if(strlen($caption)>255){
                        array_push($errors,"The post description is too long please keep it below 255 characters!");
                    }
                    if(strlen($geotag)>50){
                        array_push($errors,"The post geotag is too long please keep it below 50 characters!");
                    }
                    if(strlen($tags_string)>50){
                        array_push($errors,"The post tags is too long please keep it below 50 characters!");
                    }
                    if(count($errors)==0){
                    
                    $query = "SELECT * FROM Users WHERE username = '$username' ";
                    
                    $results = mysqli_query($db, $query);
                    
                    $user = mysqli_fetch_assoc($results);
                    
                    $id = ($user['userID']);
                    
                    // outputting data to see if it stores it correctly
                    $challengename = $_POST['challengename'];
                    $q = "SELECT * FROM Challenge WHERE challengeName='$challengename'";
                    $results = mysqli_query($db, $q);
                    $challenge = mysqli_fetch_assoc($results);
                    $challengeid= $challenge['challengeID'];
                    $startDate = $challenge['startDate'];
                    $currDate1 = date("Y-m-d"); //today's date
                    $currDate1 = new DateTime($currDate1); // convert into seconds
                    $startdate1 = new DateTime($startDate);
                    
                    if (!$challengeid){
                        $sql = "INSERT INTO Posts (imgSrc, geotag, datetime, description, likes, userID) VALUES ('$dest_path', '$geotag', '$datetime', '$caption', '$p' ,'$id')";
                    } else  {
                        $sql = "INSERT INTO Posts (imgSrc, geotag, datetime, description, likes, userID, challengeID) VALUES ('$dest_path', '$geotag', '$datetime', '$caption', '$p' ,'$id', '$challengeid')";
                    }
        
                    if ($db->query($sql) === TRUE) {
                    } else {
                        echo "<br><br><br><br>it didn't work" . $sql . "<br>" . $conn->error;
                    }
        
                    
                    $query2 = "SELECT * FROM Posts WHERE imgSrc = '$dest_path' AND userID = '$id' ";
                    
                    $results2 = mysqli_query($db, $query2);
                    
                    $post = mysqli_fetch_assoc($results2);
                    
                    $postid = ($post['postID']);
                    
                    if(count($tags_array > 1)) {
                        foreach($tags_array as $tag) {
                            $sq2 = "INSERT INTO Tags (postID, tag) VALUES ('$postid' , '$tag')";
                            mysqli_query($db, $sq2);
                        }
                    }
                    
                    // insert in to posts in channel
                    $channelname = $_POST['channelname'];
                    $q = "SELECT * FROM Channel WHERE channelName='$channelname'";
                    $results = mysqli_query($db, $q);
                    $channel = mysqli_fetch_assoc($results);
                    $channelid = $channel['channelID'];
                    
                    // need to get postID
                    $q = "SELECT * FROM Posts WHERE datetime='$datetime' AND userID='$id' AND imgSrc='$dest_path'";
                    $results = mysqli_query($db, $q);
                    $post = mysqli_fetch_assoc($results);
                    $postid = $post['postID'];
                    if ($channelid){
                        $sql100 = "INSERT INTO postsInChannel (channelID, postID) VALUES ('$channelid' , '$postid')";
                        mysqli_query($db, $sql100);
                    }
                    
                
                    $succesfullMessage = "Successfully created post!";
                    $messageecho = "<h5> <a style=\"color:#20b2aa\"  > <u> $succesfullMessage</u></a> </h5> ";
                    echo $messageecho;
                    } else {
                        header(newPost.php);
                       
                    }
            } else {
                echo "Error posting picture.";
            }
        }
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
    function previewFile(input){
        var file = $("input[type=file]").get(0).files[0];
 
        if(file){
            var reader = new FileReader();
 
            reader.onload = function(){
                $("#previewImg").attr("src", reader.result);
            }
 
            reader.readAsDataURL(file);
        }
    }
    </script>
</head>
    <body>
        <style>
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary
        {
            background-color:#8FBC8F;
            border-color: #8FBC8F;
            color:#fff;
        }
        .btn-primary
        {
            color:#fff;
            background-color:#20B2AA;
            border-color: #20B2AA;
        }
    </style>
        <?php include "includes/header.html" ?>
        <?php include "includes/carousel.html" ?>
        <!-- Layout - columns -->
        <!-- Create new Post -->
        <div class="container">
            <br><br>
                <div class = "row row-cols-3">
                <div class = "col-lg-3 col-md-4 col-5 d-block">
                <div class = "card border-2 border-dark shadow-lg">
                <div class = "card body border-0 m-2" align="center">
                   <h2>Create Post</h2>
                 </div></div>
                </div>
                <div class = "col-lg-5 col-md-4">
                </div>
                <div class = " d-none d-md-block col-lg-3 col-md-4">
                    <div class = "card border-2 border-dark shadow-lg">
                    <div class = "card body border-0 m-2" align="center">
                    <h2>Notifications</h2>
                    </div>
                    </div>
            </div>
        </div><br>
        <div class="row row-cols-2">
        
        <!-- Main content of current page -->
        <div class="col-md-8 col-12 d-block">
        <div class="border border-2 border-dark rounded-3 shadow-lg p-3 mb-5 bg-body">
        
        <script>
            $(':input[type=file]').change( function(event) {
	        var tmppath = URL.createObjectURL(event.target.files[0]);
	        $(this).next("img").attr('src',tmppath);
            });
        </script>
        <style>
            .center {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 50%;
                }
                
                input[type='file'] {
                color: rgba(0, 0, 0, 0)
                }
        </style>
        
        
        <form method="post" enctype="multipart/form-data">

                 <?php        echo $messageecho; ?>
            <label  id="imagetext">Select Image:</label>
       
            <img class="center" id="previewImg" src="" width="500" heigth="500" alt=""><br>
            <input  type="file" id="img" name="uploadedFile" onchange="previewFile(this);" accept="image/*"  required><br>
            
            <label id="captiontext">Image Description:</label><br>
            <input  type="text" id="caption" name="caption" required><br>

            <label id="tagstext">Image Tags (seperated by space):</label><br>
            <input type="text" id="tags" name="tags" required><br>
            
            <label id="geotagtext">Geotag (location):</label><br>
            <input type="text" id="geotag" name="geotag" required><br>
            
            <br><br><label>Participate in Challenge (optional): <br><a style="color:coral">-You must join a challenge before you can upload a post for the challenge.<br> -Only showing Active challenges you have joined.</a></label><br>
            
            <?php
                $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                $username = $_SESSION['username'];
                $queryChallenge = "SELECT * FROM Users WHERE username = '$username' ";
                $resultsChallenge = mysqli_query($db, $queryChallenge);
                $user2 = mysqli_fetch_assoc($resultsChallenge);
                $id = ($user2['userID']);
                $getChallengeIDs = "SELECT * FROM Participants WHERE userID = '$id'";
                $resultsChallengeid = mysqli_query($db, $getChallengeIDs);
                $challenge_ids = array();
                $challenge_index = 0;
                while ($row = mysqli_fetch_array($resultsChallengeid)) {
                    $temp_CID = $row['challengeID'];
                    $challenge_ids[$challenge_index] = $temp_CID;
                    $challenge_index++;
                }
                
                
                $challenge_names = array();
                $id_array_size = count($challenge_ids);
                $index = 0;
                for($index; $index < $id_array_size; $index++) {
                    $tmp_id = $challenge_ids[$index];
                    $name = "SELECT * FROM Challenge WHERE challengeID = '$tmp_id' ";
                    $result_name = mysqli_query($db,$name); 
                    $tmp_name1 = mysqli_fetch_assoc($result_name);
                    $tmp_name2 = $tmp_name1['challengeName'];
                    $startDate = $tmp_name1['startDate'];
                    $endDate = $tmp_name1['endDate'];
                    $currDate1 = date("Y-m-d"); //today's date
                    $currDate1 = new DateTime($currDate1); // convert into seconds
                    $startdate1 = new DateTime($startDate);
                    $enddate1 = new DateTime($endDate);
                    
                    if ($currDate1 >= $startdate1 && $currDate1 <= $enddate1 ){
                        $challenge_names[$index] = $tmp_name2; }
                }
                $index = 0;
                $size = count($challenge_names);
                
                echo "<select name = 'challengename'>";
                
                echo "<option value = '" . "Select" ."' >" ."Select" . "</option>";
                for($index; $index < $size; $index++) {
                    echo "<option value = '" . $challenge_names[$index] ."' >" .$challenge_names[$index] . "</option>";
                }
                echo "</select>";
            ?>
            <br><br>
            
            <br><label>Add post to Channel (optional): <br><a style="color:coral">-You must join a channel before you can upload a post to the channel.<br> -Only showing channels you have joined.</a></label><br>
            
            <?php
                $db = mysqli_connect('localhost', 'g65postn_root', 'E7JCqxDS5wIWgBkwlZOQ', 'g65postn_jat4');
                $username = $_SESSION['username'];
                $queryChannel = "SELECT * FROM Users WHERE username = '$username' ";
                $resultsChannel = mysqli_query($db, $queryChannel);
                $user2 = mysqli_fetch_assoc($resultsChannel);
                $id = ($user2['userID']);
                $getChannelIDs = "SELECT * FROM usersInChannel WHERE userID = '$id'";
                $resultsChannelid = mysqli_query($db, $getChannelIDs);
                $channel_ids = array();
                $channel_index = 0;
                while ($row = mysqli_fetch_array($resultsChannelid)) {
                    $temp_CID = $row['channelID'];
                    $channel_ids[$channel_index] = $temp_CID;
                    $channel_index++;
                }
                
                
                $channel_names = array();
                $id_array_size = count($channel_ids);
                $index = 0;
                for($index; $index < $id_array_size; $index++) {
                    $tmp_id = $channel_ids[$index];
                    $name = "SELECT * FROM Channel WHERE channelID = '$tmp_id' ";
                    $result_name = mysqli_query($db,$name); 
                    $tmp_name1 = mysqli_fetch_assoc($result_name);
                    $tmp_name2 = $tmp_name1['channelName'];
                    $channel_names[$index] = $tmp_name2;
                }
                $index = 0;
                $size = count($channel_names);
                
                echo "<select name = 'channelname'>";
                
                echo "<option value = '" . "Select" ."' >" ."Select" . "</option>";
                for($index; $index < $size; $index++) {
                    echo "<option value = '" . $channel_names[$index] ."' >" .$channel_names[$index] . "</option>";
                }
                echo "</select>";
            ?>
       
            
            <div class="text-center">
            <input id="submit" type="submit" name="uploadBtn" class="btn btn-primary btn-sm" value="Create Post" onclick="return confirm('We have a strict policy of only allowing posts of nature, objects and animals. Does your post follow our guidelines?')"/>
            <?php include('errors.php'); ?>
            </div>
            
            
            
        </form>
    </div>
    </div>
        <?php include "includes/notifications.php" ?>
    </div>
    </div>
    <?php include "includes/footer.php" ?>
    </body>
    <br>
    <br>
    <br>
</html>



    



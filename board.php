<?php
    include("./config/config.php");
    include("./config/functions.php");

    // start session
    session_start();
    if (!isset($_SESSION['loggedIn']) || !isset($_SESSION['userEmail']) || !isset($_SESSION['userID']) || !isset($_SESSION['departmentID'])) {
        // Redirect to index.php if any of the session variables are not set
        header("location: index.php");
        exit();
    }

    if(isset($_GET['id'])){
        $id = cleanInput($_GET['id']);
        $id = sanitizeNumberInput($id);

        if(empty($id)){
            header("Location: home.php");
            exit();
        }
        
        $query = "SELECT board.*, department.department FROM board LEFT JOIN department ON board.departmentID = department.id
                    WHERE board.id = ".$id;

        $result = mysqli_query($connection, $query);
        
        if(mysqli_num_rows($result) < 1){
            header("Location: home.php");
            exit();
        }

        $getRow = mysqli_fetch_assoc($result);
    } 

    if(isset($_POST['feedback'])){
        $ip_addr = $_SERVER['REMOTE_ADDR'];
        $feedback = mysqli_real_escape_string($connection, sanitizeStringInput(cleanInput($_POST['message'])));
        $feedback = htmlspecialchars($feedback);
        $boardId = $_POST['boardID'];
        
        if(empty($feedback)){
            echo "<script>alert('Feedback is required.');</script>";
        }else{
            $now = date('Y-m-d H:i:s');
            $userId = $_SESSION['userID'];
            if(mysqli_query($connection, "INSERT INTO feedback(content, boardID, userID, created_at, ip_addr) VALUES ('$feedback', $boardId, $userId, '$now', '$ip_addr')")){
                echo "<script>alert('Your feedback has been created.');</script>";
            }else{
                echo "Error : ".mysqli_error($connection);
            }
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $getRow['title'] ?> | CUEA Online Notice Board</title>
    <link rel="stylesheet" href="assets/css/board.css" />
    <script src="assets/js/app.js"></script>
</head>
<body>
    <div class="header">
        <h5 class="logo">CUEA Online Notice Board</h5>
        <div class="nav">
            <a href="home.php">Home</a>
            <a href="search.php">Search</a>
            <a href="contact.php">Contact Us</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <h1><?php echo $getRow['title']; ?></h1>
        <div class="first-wrapper">
            <div class="date">Created At <?php if(isset($getRow['updated_at'])){ echo $getRow['updated_at']; }else{ echo $getRow['created_at']; } ?>  </div>
            <?php 
                if(isset($getRow['department'])){ 
            ?>
                    <div class="department"><?php echo $getRow['department']; ?></div>
            <?php
               }else{ echo 'General'; }
            ?>  
            <div class="category"><?php if($getRow['is_notice']){ echo "Notice"; }else{ echo "Announcement"; } ?></div> 
        </div>
        <div class="second-wrapper">
            <div class="content">
                <p>
                    <?php echo $getRow['content'] ?>
                </p>
            </div>
            <?php 
                if(isset($getRow['media'])){
                    $cat="";
                    $imageName = $getRow['media'];
                    if($getRow['is_notice']){ $cat= "notice"; }else{ $cat= "announcement"; }
            ?>
                    <div class="media">
                        <img src="<?php echo 'assets/images/'.$cat.'/'.$imageName; ?>" />
                    </div>
            <?php } ?>
        </div>
    </div>
    <div class="feedbacks">
            <h4> Feedbacks </h4>
            <div class="box">
                <?php 
                    $getFeedbacks = "SELECT user.firstname, user.lastname, user.id AS UID, feedback.content, feedback.boardID AS FBID,
                                     feedback.userID, board.id as BID, feedback.created_at, feedback.updated_at, feedback.id as FID, feedback.is_blocked
                                        FROM user
                                        INNER JOIN feedback ON feedback.userID = user.id
                                        INNER JOIN board ON feedback.boardID = board.id
                                        WHERE board.id = $id ORDER BY feedback.created_at DESC";
                    $results = mysqli_query($connection, $getFeedbacks);
                    if(mysqli_num_rows($results) > 0){
                        while($data = mysqli_fetch_assoc($results)){
                            if( (int) $data['is_blocked'] == 0 ){
                ?>
                                <div class="single-feedback">
                                    <div class="feedbacker">
                                        <span><?php echo $data['firstname']." ".$data['lastname']; ?></span>
                                        <span><?php echo $data['created_at']; ?></span>
                                    </div>
                                    <p><?php echo $data['content']; ?></p>
                                    <?php 
                                        // if a user is the owner of the feedback show him/her this options.
                                        if( isset($_SESSION['loggedIn']) && (int)$_SESSION['userID'] === (int)$data['UID'] ) {
                                    ?>
                                            <div class="flinks">
                                                <a href="update-feedback.php?feedbackID=<?php echo $data['FID']; ?>&board=<?php echo $id; ?>">Edit</a>
                                                <a href="#" onclick="deleteFeedback(<?php echo $data['FID']; ?>,<?php echo $id; ?>)" >Delete</a>
                                            </div>        
                                    <?php
                                        }
                                    ?>
                                </div>
                            <?php } else if( (int) $data['is_blocked'] == 1 && (int) $data['UID'] == (int) isset($_SESSION['userID']) ) { ?>
                                <div class="single-feedback-red">
                                    <span class='message'><p>This feedback is blocked, no other user apart from you can see it.</p></span>
                                    <div class="feedbacker">
                                        <span><?php echo $data['firstname']." ".$data['lastname']; ?></span>
                                        <span><?php echo $data['created_at']; ?></span>
                                    </div>
                                    <p><?php echo $data['content']; ?></p>
                                    <?php 
                                        if( isset($_SESSION['loggedIn']) ==true && $data['UID'] == isset($_SESSION['userID']) ) {
                                    ?>
                                            <div class="flinks">
                                                <a href="update-feedback.php?feedbackID=<?php echo $data['FID']; ?>&board=<?php echo $id; ?>">Edit</a>
                                                <a href="#" onclick="deleteFeedback(<?php echo $data['FID']; ?>,<?php echo $id; ?>)" >Delete</a>
                                            </div>        
                                    <?php
                                        }
                                    ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                <?php  } else { ?>
                    <h5> No feedbacks found. </h5>
                <?php } ?>
            </div>
            <?php 
                if( isset($_SESSION['loggedIn']) == true ){
            ?>
                    <div class="fbform">
                        <form method="post" onsubmit="return checkFeedback()">
                            <label for='message'>Your Feedback</label><br/>
                            <input type="hidden" name="boardID" value="<?php echo $id; ?>" />
                            <textarea name="message" id="message" placeholder="Type your feedback here..." rows=5></textarea>
                            <br/>
                            <button type="submit" name="feedback">Send Feedback</button>
                        </form>
                    </div>
            <?php } else { ?>
                <div class="">
                    <a href="login.php">Please login to leave a feedback</a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="footer">
        <!-- <div class="links">
            <h6>Links</h6>
            <a href="./admin">Login as Admin</a>
            <a href="./lecturer">Login as Lecturer</a>
        </div> -->
        <div class="copy">
            <p> &copy; Copyright 2024 CUEA. All Rights Reserved.
        </div>
    </div>
    <?php mysqli_close($connection); ?>
</body>
</html>
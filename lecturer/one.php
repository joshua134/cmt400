<?php
    include("../config/config.php");
    include("../config/functions.php");

    // start session
    session_start();

    // check if user is logged in and we have a user id
    if( empty($_SESSION['lecturerLoggedIn']) || empty($_SESSION['lecID'])  || empty($_SESSION['lecEmail']) || empty($_SESSION['lecLastname']) || empty($_SESSION['lecDepId']) ){
        // if false return user to login page
        header("Location: index.php");
        // prevent further executions.
        exit();
    }

    $lecID = $_SESSION['lecID'];

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
            header("Location: dashboard.php");
            exit();
        }

        $getRow = mysqli_fetch_assoc($result);


    }
    
    if(isset($_POST['feedbacSubmitted']) ){
        $feedback = sanitizeStringInput(cleanInput($_POST['message']));
        $feedback = mysqli_real_escape_string($connection, $feedback);
        $boardID = sanitizeNumberInput($_POST['boardID']);    

        if(empty($feedback)){
            echo "<script>alert('Feedback is required.');</script>";
        }else{
            $now = date('Y-m-d H:i:s');
            $userId = $_SESSION['lecID'];
            if(mysqli_query($connection, "INSERT INTO feedback(content, boardID, userID, created_at) VALUES ('$feedback', $boardID, $userId, '$now')")){
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
    <title><?php echo $getRow['title']; ?> </title>
    <link rel="stylesheet" href="../assets/css/lecturer/one.css" />
    <script src="../assets/js/lecturer/app.js"></script>
</head>
<body>
    <div class="header">
        <h5 class="logo">Welcome Mr/Mrs <?php echo $_SESSION['lecLastname'] ?>,</h5>
        <div class="nav">
            <a href="dashboard.php">Home</a>
            <a href="notice.php">Notice</a>
            <a href="announcement.php">Announcement</a>
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
               }
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
                                     feedback.userID AS FBUID, board.id as BID, feedback.created_at, feedback.updated_at, feedback.id as FID, feedback.is_blocked
                                        FROM user
                                        INNER JOIN feedback ON feedback.userID = user.id
                                        INNER JOIN board ON feedback.boardID = board.id
                                        WHERE board.id = $id ORDER BY feedback.created_at DESC";
                    $results = mysqli_query($connection, $getFeedbacks);
                    if(mysqli_num_rows($results) > 0){
                        while($data = mysqli_fetch_assoc($results)){
                ?>
                            <div class="single-feedback">
                                <div class="feedbacker">
                                    <span><?php echo $data['firstname']." ".$data['lastname']; ?></span>
                                    <span><?php echo $data['created_at']; ?></span>
                                </div>
                                <p><?php echo $data['content']; ?></p>
                                
                                <div class="flinks">
                                    <?php if( (int) $data['is_blocked'] == 1 ){ ?>
                                        <a href="#" onclick="unBlockFeedback(<?php echo $data['FID']; ?>, <?php echo $getRow['id']; ?>)" >Unblock</a>
                                    <?php } else { ?>
                                        <a href="#" onclick="blockFeedback(<?php echo $data['FID']; ?>, <?php echo $getRow['id']; ?>)" >Block</a>
                                    <?php } ?>

                                    <?php if( (int) $data['FBUID'] == (int) $lecID ){ ?>
                                        <a href="update-feedback.php?feedbackID=<?php echo $data['FID']; ?>">Edit</a>
                                    <?php } ?>

                                        <a href="#" onclick="deleteFeedback(<?php echo $data['FID']; ?>, <?php echo $getRow['id']; ?>)" >Delete</a>
                                
                                
                                </div>        
                                
                            </div>
                        <?php } ?>
                <?php  } else { ?>
                    <h5> No feedbacks found. </h5>
                <?php } ?>
            </div>
            <div class="fbform">
                <form method="post" onsubmit="return checkFeedback()">
                    <label for='message'>Your Feedback</label><br/>
                    <input type="hidden" name="boardID" value="<?php echo $id; ?>" />
                    <textarea name="message" id="message" placeholder="Type your feedback here..." rows=5></textarea>
                    <br/>
                    <button type="submit" name="feedbacSubmitted">Send Feedback</button>
                </form>
            </div>
    
        </div>
    </div>
</body>
</html>
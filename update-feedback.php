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


    if( isset($_GET['feedbackID']) && isset($_GET['board']) ){
        $fID = cleanInput($_GET['feedbackID']);
        $fID = sanitizeNumberInput($fID);
        $bID = cleanInput($_GET['board']);
        $bID = sanitizeNumberInput($bID);
        

        if(empty($fID)){
            header("Location: index.php");
            exit();
        }
        
        $query = "SELECT * FROM feedback WHERE id = ".$fID;

        $result = mysqli_query($connection, $query);
        
        if(mysqli_num_rows($result) < 1){
            header("Location: board.php");
            exit();
        }else{
            $getRow = mysqli_fetch_assoc($result);
            if( (int)$getRow['userID'] == (int)$_SESSION['userID'] ){
                //
            }else{
                echo "<script>alert('Sorry! You are not the creator of this feedback.');</script>";
                header("Refresh:0; url=board?id=".$bID);
                exit();
            }
        }
    } 
    
    if (isset($_POST['update'])){
        $content = htmlspecialchars(sanitizeStringInput(cleanInput($_POST['message'])));
        $id = sanitizeNumberInput($_POST['id']);
        $boardID = sanitizeNumberInput($_POST['boardID']);
        $now = date('Y-m-d H:i:s');
        $result = mysqli_query($connection, "SELECT * FROM feedback WHERE id =".$id);
        if(mysqli_num_rows($result)> 0){
            if(mysqli_query($connection, "UPDATE feedback SET content =  '$content', updated_at  = '$now' WHERE id = ".$id)){
                echo "<script>alert('Your feedback has been updated successfully.');</script>";
                header("Location: board.php?id=".$boardID);
            }else{
                echo "Error : ".mysqli_error($connection);
            }
        }else{
            echo "Error : ".mysqli_error($connection);
        }

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Feedback</title>
    <link rel="stylesheet" href="assets/css/feedback.css" />
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
        <h4> Update your feedback</h4>
        <form method="post" onsubmit="return checkFeedbackUpdate()">
            <label for="message">Feedback</label>
            <br/>
            <textarea name="message" id="message" rows=15><?php echo $getRow['content']; ?></textarea>
            <input type="hidden" name="id" value="<?php echo $getRow['id']; ?>" />
            <input type="hidden" name="boardID" value="<?php echo $bID; ?>" />
            <br/>
            <button type="submit" name="update">Update Feedback</button>
        </form>
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
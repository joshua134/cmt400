<?php
    include("../config/config.php");
    include("../config/functions.php");

    // start session
    session_start();

    // check if user is logged in and we have a user id
    if( !isset($_SESSION['lecturerLoggedIn']) || !isset($_SESSION['lecID'])  || !isset($_SESSION['lecEmail']) || !isset($_SESSION['lecLastname']) || !isset($_SESSION['lecDepId']) ){
        // if false return user to login page
        header("Location: index.php");
        // prevent further executions.
        exit();
    }

    $lecID = $_SESSION['lecID'];

    if(isset($_GET['feedbackID'])){
        $fID = cleanInput($_GET['feedbackID']);
        $fID = sanitizeNumberInput($fID);

        if(empty($fID)){
            header("Location: index.php");
            exit();
        }
        
        $query = "SELECT * FROM feedback WHERE id = ".$fID;

        $result = mysqli_query($connection, $query);


        if(mysqli_num_rows($result) < 1){
            header("Location: dashboard.php");
            exit();
        }else{
            $getRow = mysqli_fetch_assoc($result);
        }
    } 

    if (isset($_POST['update'])){
        $content = htmlspecialchars(sanitizeStringInput(cleanInput($_POST['message'])));
        $id = sanitizeNumberInput($_POST['id']);
        $now = date('Y-m-d H:i:s');
        $result = mysqli_query($connection, "SELECT * FROM feedback WHERE id =".$id);
        if(mysqli_num_rows($result)> 0){
            if(mysqli_query($connection, "UPDATE feedback SET content =  '$content', updated_at  = '$now' WHERE id = ".$id)){
                echo "<script>alert('Your feedback has been updated successfully.');</script>";
                header("Location: dashboard.php");
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
    <title>Update Feedback </title>
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
        <h4> Update your feedback</h4>
        <form method="post" onsubmit="return checkFeedbackUpdate()">
            <label for="message">Feedback</label><br/>
            <br/>
            <textarea name="message" id="message" rows="15" cols="10"><?php echo $getRow['content']; ?></textarea>
            <input type="hidden" name="id" value="<?php echo $getRow['id']; ?>" />
            <br/>
            <button type="submit" name="update">Update Feedback</button>
        </form>
    </div>
</body>
</html>
<?php
    include("../config/config.php");
    include("../config/functions.php");


    // start session
    session_start();

    // check if user is logged in and we have a user id
    if(!isset($_SESSION['adminloggedIn'])  || !isset($_SESSION['adminID'])  || !isset($_SESSION['adminEmail']) || !isset($_SESSION['adminLastname']) ){
        // if false return user to login page
        header("Location: index.php");
        // prevent further executions.
        exit();
    }


    if(isset($_GET['noticeID'])){
        $id = trim($_GET['noticeID']);
        $id = sanitizeNumberInput($id);

        $sql = "DELETE FROM board WHERE is_announcement = true AND  id = ".$id;
        
        if(mysqli_query($connection, $sql)){
            echo "<script>alert('Announcement deleted successfully.');</script>";
            header("Refresh: 0; url=notice.php");
            exit();
        }else{
            echo "<script>alert('Error in deleting Announcement.');</script>";
            echo "Error : => ".mysqli_error($connection);
        }

        mysqli_close($connection);
        exit();

    }

?>
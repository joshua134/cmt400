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

    if(isset($_GET['announcementID'])){
        $id = trim($_GET['announcementID']);
        $id = sanitizeNumberInput($id);

        $sql = "DELETE FROM board WHERE is_announcement=true AND id = ".$id;
        
        if(mysqli_query($connection, $sql)){
            echo "<script>alert('Announcement deleted successfully.');</script>";
            header("Refresh: 0; url=announcement.php");
            exit();
        }else{
            echo "<script>alert('Error in deleting Announcement.');</script>";
            echo "Error : => ".mysqli_error($connection);
        }

        mysqli_close($connection);
    }

?>
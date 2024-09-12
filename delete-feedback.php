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

    // before deleting check if user is the owner.
    if( isset($_GET['id']) && isset($_GET['boardID']) ){

        $fID = cleanInput($_GET['id']);
        $fID = sanitizeNumberInput($fID);

        $bID = cleanInput($_GET['boardID']);
        $bID = sanitizeNumberInput($bID);

        if(empty($fID)){
            header("Location: home.php");
            exit();
        }
        
        $query = "SELECT * FROM feedback WHERE id = ".$fID;

        $result = mysqli_query($connection, $query);
        $getRow = mysqli_fetch_assoc($result);

        if( (int)$getRow['userID'] == (int)$_SESSION['userID'] ){
            if(mysqli_query($connection, "DELETE FROM feedback WHERE id = ".$fID)){
                echo "<script>alert('Feedback deleted successfully.');</script>";
                header("Refresh:0; url=board.php?id=".$bID);
                exit();
            }else{
                echo "<script>alert('Error in deleting Feedback.');</script>";
                echo "Error : => ".mysqli_error($connection);
            }
        }else{
            echo "<script>alert('Sorry! You are not the creator of this feedback.');</script>";
            header("Refresh:0; url=home.php");
            exit();
        }
    }
    mysqli_close($connection);

?>
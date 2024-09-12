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

    if( isset($_GET['fid']) && isset($_GET['bid']) ){

        $fID = cleanInput($_GET['fid']);
        $fID = sanitizeNumberInput($fID);

        $bID = cleanInput($_GET['bid']);
        $bID = sanitizeNumberInput($bID);

        if(empty($fID) || empty($bID)){
            header("Location: dashboard.php");
            exit();
        }
        
        $query = "SELECT * FROM feedback WHERE id = $fID AND boardID = $bID LIMIT 1";

        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result)>0){
            if(mysqli_query($connection, "UPDATE feedback set is_blocked=false WHERE id = $fID AND boardID=$bID")){
                echo "<script>alert('Feedback unblocked successfully. \nPublic can see this  feedback.');</script>";
                header("Refresh:0; url=dashboard.php");
                exit();
            }else{
                echo "<script>alert('Error in blocking Feedback.');</script>";
                echo "Error : => ".mysqli_error($connection);
            }
        }else{
            echo "<script>alert('Feedback does not exist.');</script>";
            header("Refresh:0; url=dashboard.php");
            exit();
        }
    }
    
    mysqli_close($connection);

?>
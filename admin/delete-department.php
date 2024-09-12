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


    if(isset($_GET['departmentID'])){
        $id = trim($_GET['departmentID']);
        $id = sanitizeNumberInput($id);

        $sql = "DELETE FROM department WHERE id = ".$id;
        
        if(mysqli_query($connection, $sql)){
            echo "<script>alert('Department deleted successfully.');</script>";
        }else{
            echo "<script>alert('Error in deleting department.');</script>";
            echo "Error : => ".mysqli_error($connection);
        }

        // go back to department page
        header("Location: department.php");
        mysqli_close($connection);
        exit();

    }

?>
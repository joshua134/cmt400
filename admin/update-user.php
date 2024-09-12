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


    if(isset($_GET['userID'])){
        $id = trim($_GET['userID']);
        $id = sanitizeNumberInput($id);

        $query = "  SELECT department.id as dID, department.department, user.* FROM user
                        JOIN department ON user.departmentID = department.id
                        WHERE user.id = ".$id;

        $result = mysqli_query($connection, $query);
        $getRow = mysqli_fetch_assoc($result);
    }

    if(isset($_POST['update'])){
        $uid = sanitizeNumberInput(cleanInput($_POST['uid']));
        if(!isset($_POST['status'])){
            echo "<script>alert('Please select a status');</script>";
        }else{
            $status = $_POST['status'];
            // if 1 it is to block
            // if 0 it is to unblock
            if( strcmp($status, '1') == 0 ){
                if(mysqli_query($connection, "UPDATE user SET is_blocked = true WHERE id = ".$uid ) ){
                    echo "<script>alert('Account blocked successfully.');</script>";
                }else{
                    echo " Error ".mysqli_error($connection);
                }
            }elseif( strcmp($status, '2') == 0 ){
                if(mysqli_query($connection, "UPDATE user SET is_blocked = false WHERE id = ".$uid ) ){
                    echo "<script>alert('Account unblocked successfully.');</script>";
                }else{
                    echo " Error ".mysqli_error($connection);
                }
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="../assets/css/admin/update-user.css"/>
</head>
<body>
    <div class="header">
        <a href="dashboard.php">Home</a> / <a href="user.php">Users</a> / <a href="create-lecturer.php">Create Lecturer</a> / <a href="logout.php">Logout</a>

    </div>
    <div class="container">
        <div class="box">
            <h6>Update <?php echo $getRow['lastname'] ?>'s Profile</h6>
            <form method="post">
                <input type="hidden" name="uid" value="<?php echo $getRow['id']; ?>" />
                <label for="status">Status</label><br/>
                <input type="radio" name="status" id="status" value="1"  /> Block Account
                <input type="radio" name="status" id="status"  value="2" /> Unblock Account
                <br/>
                <button type="submit" name="update">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>
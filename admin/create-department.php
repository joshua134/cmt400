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


    if(isset($_POST['create'])){
        $department = cleanInput($_POST['title']);
        $department = sanitizeStringInput($department);

        if(empty($department)){
            echo "<script>alert('Department Name is required.');</script>";
        }else{

            // check if department already exists.
            $result = mysqli_query($connection, "SELECT department FROM department WHERE department = '$department'");
            if(mysqli_num_rows($result) > 0){
                echo "<script>alert('Department of ".mysqli_fetch_assoc($result)['department']." already exists in the system.');</script>";
            }else{
                // add department
                if(mysqli_query($connection, "INSERT INTO department(department) VALUES ('$department')")){
                    echo "<script>alert('Department added successfully.');</script>";
                }else{
                    echo "Error: ". mysqli_error($connection);
                    echo "<script>alert('Error in adding department to system.');</script>";
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
    <title>Create Department</title>
    <link rel="stylesheet" href="../assets/css/admin/create.css" type="text/css"/>
    <script src="../assets/js/admin/app.js"></script>
</head>
<body>
    <div class="header">
        <a href="dashboard.php">Home</a> / <a href="department.php">Create Department</a> / <a href="department.php">Departments</a> / <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <div class="wrapper">
            <h5>Create Department</h5>
            <form method="post" action="create-department.php"  onsubmit="return departmentCreate()">
                <label for="title">Department Name</label><br/>
                <input type="text" placeholder="Title" id="title" name="title" />
                <br/>
                <button type="submit" name="create">Create Department</button>
            </form>
            
        </div>
    </div>
    <?php mysqli_close($connection); ?>
</body>
</html>
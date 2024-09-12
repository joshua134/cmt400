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
        $departmentID = cleanInput($_GET['departmentID']);
        $departmentID = sanitizeNumberInput($departmentID);

        $result = mysqli_query($connection, "SELECT department FROM department WHERE id = '$departmentID' LIMIT 1");
        $getRow = mysqli_fetch_assoc($result);
        
        if(mysqli_num_rows($result) < 1){
            header("Location: department.php");
            exit();
        }
    }else if(isset($_POST['update'])){
        $department = cleanInput($_POST['title']);
        $id = cleanInput($_POST['id']);
        
        $department = sanitizeStringInput($department);
        $id = sanitizeNumberInput($id);

        if(empty($department) || empty($id)){
            echo "<script>alert('Department Name and ID are required.');</script>";
        }else{

            $sql = "UPDATE department set department = '$department' WHERE id = '$id'";
            if(mysqli_query($connection, $sql)){
                echo "<script>alert('Department updated successfully.');</script>";
            }else{
                echo "Error: ". mysqli_error($connection);
                echo "<script>alert('Error in updating  department.');</script>";
            }

            header("Location: department.php");
            exit();
        }
    }else{
        header("Location: department.php");
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Department</title>
    <link rel="stylesheet" href="../assets/css/admin/create.css" type="text/css"/>
    <script src="../assets/js/admin/app.js"></script>
</head>
<body>
    <div class="header">
        <a href="dashboard.php">Home</a> / <a href="department.php">Update Department</a> / <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <div class="wrapper">
            <h5>Update Department</h5>
            <form method="post" action="update-department.php"  onsubmit="return departmentUpdate()">
                <label for="title">Department Name</label><br/>
                <input type="text" placeholder="Title" id="title" name="title" style="width: 400px;"
                            value="<?php if(isset($getRow['department'])){ echo $getRow['department']; }else{ echo ''; } ?>"
                />
                <input type="hidden" name="id" value='<?php if(isset($departmentID)) echo $departmentID; ?>' />
                <br/>
                <button type="submit" name="update">Update Department</button>
            </form>
            
        </div>
    </div>
    <?php mysqli_close($connection); ?>
</body>
</html>
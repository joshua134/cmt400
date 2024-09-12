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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department</title>
    <link rel="stylesheet" href="../assets/css/admin/department.css" type="text/css"/>
    <script src="../assets/js/admin/app.js"></script>
</head>
<body>
    <div class="header">
        <a href="dashboard.php">Home</a> / <a href="department.php">Departments</a> / <a href="create-department.php">Create Department</a> / <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <div class="wrapper">
            <h5>Departments</h5>
            <?php 
                $query = "SELECT * FROM department ORDER BY id DESC";
                $stmt = $connection->query($query);
                $departments = $stmt->fetch_all(MYSQLI_ASSOC);
                
                if(!empty($departments)){
            ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Department Name</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($departments as $department){
                    ?>
                            <tr>
                                <td><?php echo $department['id']; ?></td>
                                <td><?php echo "Department of ".$department['department']; ?></td>
                                <td><a href="<?php echo 'update-department.php?departmentID='.$department['id']; ?>">Edit Notice</a></td>
                                <td><a href="#" onclick="confirmDelete(<?php echo $department['id']; ?>)">Delete Notice</a></td>
                            </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
            <?php } else { ?>
                <h6> No departments found. </h6>
             <?php } ?>
        </div>
        </div>
    </div>
    <?php mysqli_close($connection); ?>
</body>
</html>
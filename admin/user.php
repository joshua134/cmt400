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
        <a href="dashboard.php">Home</a> / <a href="user.php">Users</a> / <a href="create-lecturer.php">Create Lecturer Account</a> / <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <div class="wrapper">
            <h5>Users</h5>
            <?php 
                $query = "SELECT * FROM user ORDER BY id DESC";
                // $stmt = $connection->query($query);
                // $users = $stmt->fetch_all(MYSQLI_ASSOC);
                $result = mysqli_query($connection, $query);
                
                if(mysqli_num_rows($result) > 0){
            ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                        <th>Admin</th>
                        <th>Lecturer</th>
                        <th>Normal User</th>
                        <th>Is Blocked</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while($user = mysqli_fetch_assoc($result)){
                    ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['lastname']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <?php if($user['is_admin']){ echo "<b>Yes</b>"; }else{ echo "<b>No</b>"; } ?>
                                </td>
                                <td>
                                    <?php if($user['is_lecturer']){ echo "<b>Yes</b>"; }else{ echo "<b>No</b>"; } ?>
                                </td>
                                <td>
                                    <?php if($user['is_normal']){ echo "<b>Yes</b>"; }else{ echo "<b>No</b>"; } ?>
                                </td>
                                <td>
                                    <?php
                                        if( (int)$user['is_blocked'] == 1){
                                    ?>
                                            <p>Yes</p>
                                    <?php 
                                        }elseif( (int) $user['is_blocked'] == 0){
                                    ?>
                                            <p>No</p>
                                    <?php  
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php echo 'update-user.php?userID='.$user['id']; ?>">Edit</a>
                                    
                                    <a href="#" onclick="confirmDeleteUser(<?php echo $user['id']; ?>)">Delete</a>
                                </td>
                            </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
            <?php } else { ?>
                <h6> No Users found. </h6>
             <?php } ?>
        </div>
        </div>
    </div>
    <?php mysqli_close($connection); ?>
</body>
</html>
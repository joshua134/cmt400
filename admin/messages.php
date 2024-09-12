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
    <title>User Messages | Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin/style.css" type="text/css"/>
    <script src="../assets/js/admin/app.js"></script>
</head>
<body>
    <div class="header">
        <h5 class="logo">CUEA Online Notice Board</h5>
        <div class="nav">
            <a href="dashboard.php">Home</a>
            <a href="user.php">Users</a>
            <a href="department.php">Departments</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container" style="margin: 15px 10px 0px 10px;" >
        <?php
            $query = "SELECT * FROM contact ORDER BY created_at DESC";
            $results = mysqli_query($connection, $query);

            if( mysqli_num_rows($results) > 0 ){
        ?>
            <table>
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Sent On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while($data = mysqli_fetch_assoc($results)){
                    ?>
                        <tr>
                            <td><?php echo $data['contact_email']; ?></td>
                            <td><?php echo $data['contact_subject']; ?></td>
                            <td><?php echo $data['created_at']; ?></td>
                            <td> <a href="view-message.php?messageID=<?php echo $data['id']; ?>">View</a> </td>
                        </tr>
                        <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <h6> No Messages sent. </h6>
            <?php } ?>
    </div>
</body>
</html>
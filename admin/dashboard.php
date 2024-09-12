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
    <title>Admin Dashboard</title>
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
            <a href="reports.php">Reports</a>
            <a href="profile.php">Profile</a>
            <a href="messages.php">Messages</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <a href="create-notice.php">Create notice</a>
        <a href="create-announcement.php"> Create announcement</a>
        <a href="create-department.php"> Create Department</a>
        <a href="create-lecturer.php"> Create Lecturer Account</a>
        
        <div class="notice">
            <h5>Notices</h5>
            <?php 
                // get all notices whether it has department or not
                $query1 = "SELECT board.id, board.title, department.department, department.id as dID, board.is_notice, board.is_announcement FROM board
                            LEFT JOIN department ON board.departmentID = department.id";

                $query = "SELECT board.id, board.title, department.department, department.id as dID, board.is_notice, board.is_announcement, count(feedback.id) as NUM_FEEDBACK
                            FROM board
                            LEFT JOIN department ON board.departmentID = department.id
                            LEFT JOIN feedback ON board.id = feedback.boardID
                            GROUP BY board.id, board.title, department.department, department.id, board.is_notice, board.is_announcement
                            ORDER BY board.created_at DESC";

                $results = mysqli_query($connection, $query);
                // count rows returned
                $rows = mysqli_num_rows($results);
                
                if( $rows > 0 ){
            ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Department</th>
                                <th>Type</th>
                                <th>Number of Feedbacks</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                    
            <?php
                    while($notice = mysqli_fetch_assoc($results)){
            ?>
                        <tr>
                            <td><?php echo $notice['id']; ?></td>
                            <td><?php echo $notice['title']; ?></td>
                            <td>
                                <?php 
                                    if( empty( $notice['department'] ) ){ 
                                        echo "No department";
                                    }else{ 
                                        echo $notice['department']; 
                                    }  
                                ?>
                            </td>
                            <td>
                                <?php 
                                    if($notice['is_notice']){
                                ?>
                                        <span>Notice</span>
                                <?php
                                    }else{
                                ?>
                                    <span>Announcement</span>
                                <?php } ?>
                            </td>
                            <td><?php echo $notice['NUM_FEEDBACK']; ?></td>
                            <td>
                                    <?php 
                                        if($notice['is_notice']){
                                    ?>
                                        <a href="<?php echo 'update-notice.php?noticeID='.$notice['id']; ?>">Edit Notice</a>
                                    <?php
                                        }else{
                                    ?>
                                        <a href="<?php echo 'update-announcement.php?announcementID='.$notice['id']; ?>">Edit Announcement</a>
                                    <?php } ?>
                            </td>
                            <td><a href="#" onclick="confirmNoticeDelete(<?php echo $notice['id']; ?>, <?php if($notice['is_notice']){ echo $notice['is_notice']; }else{ echo 0; } ?>, <?php if($notice['is_announcement']){ echo $notice['is_announcement']; }else{ echo 0; } ?>)">
                                <?php 
                                    if($notice['is_notice']){
                                ?>
                                        <span>Delete Notice</span>
                                <?php
                                    }else{
                                ?>
                                        <span>Delete Announcement</span>
                                <?php } ?>
                            </a></td>
                        </tr>
            <?php 
                    }
            ?>
                        </tbody>
                        </table>
            <?php
                } else{
            ?>
                    <h6> No notices found. </h6>
            <?php
                }
            ?>
        </div>        
    </div>
</body>
</html>
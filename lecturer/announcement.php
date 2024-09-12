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

    $lectureID = $_SESSION['lecID'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements | CUEA Online Notice Board</title>
    <link rel="stylesheet" href="../assets/css/lecturer/announcement.css" />
    <script src="../assets/js/lecturer/app.js"></script>
</head>
<body>
    <div class="header">
        <h5 class="logo">Welcome Mr/Mrs <?php echo $_SESSION['lecLastname'] ?>,</h5>
        <div class="nav">
            <a href="dashboard.php">Home</a>
            <a href="notice.php">Notice</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
        <marquee scrolldelay="200"> You can only create notices & announcement related to your department </marquee>
    </div>
    <div class="container">
        <div class='holder'>
            <a href="create-announcement.php">Create Announcement</a>
        </div>
        <div class="notice">
            <h5>Announcement</h5>
            <?php 
                // get all notices whether it has department or not
                $query1 = "SELECT board.id, board.title, board.is_notice, board.is_announcement, board.departmentID as dID, 
                            board.created_at, board.updated_at, board.is_admin, board.is_lecturer, board.lecID , department.department
                            FROM board
                            INNER JOIN user ON board.lecID = user.id
                            LEFT JOIN department ON board.departmentID = department.id WHERE is_announcement=true and board.lecID = $lectureID ORDER BY created_at DESC";


                $query = "SELECT board.id, board.title, board.is_notice, board.is_announcement, board.departmentID as dID, 
                            board.created_at, board.updated_at, board.is_admin, board.is_lecturer, board.lecID , department.department, COUNT(feedback.id) AS NUM_FEEDBACK
                            FROM board   INNER JOIN user ON board.lecID = user.id
                            LEFT JOIN department ON board.departmentID = department.id 
                            LEFT JOIN feedback ON board.id = feedback.boardID
                            WHERE is_announcement=true and board.lecID = $lectureID
                            GROUP BY  board.id, board.title, board.is_notice, board.is_announcement, board.departmentID,board.created_at,
                            board.updated_at, board.is_admin, board.is_lecturer, board.lecID , department.department
                            ORDER BY created_at DESC";

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
                                <th>Number of feedback</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                    
            <?php
                    while($announcement = mysqli_fetch_assoc($results)){
            ?>
                        <tr>
                            <td><?php echo $announcement['id']; ?></td>
                            <td><?php echo $announcement['title']; ?></td>
                            <td><?php echo $announcement['department'];  ?></td>
                            <td><?php echo $announcement['NUM_FEEDBACK']; ?></td>
                            <td>
                                    <?php 
                                        if($announcement['is_announcement']){
                                    ?>
                                        <a href="<?php echo 'update-announcement.php?announcementID='.$announcement['id']; ?>">Edit Announcement</a>
                                    <?php
                                        }
                                    ?>
                            </td>
                            <td><a href="#" onclick="confirmAnnouncementDelete(<?php echo $announcement['id']; ?>, <?php if($announcement['is_announcement']){ echo $announcement['is_announcement']; }else{ echo 0; } ?>)">
                                <?php 
                                    if($announcement['is_announcement']){
                                ?>
                                        <span>Delete Announcement</span>
                                <?php
                                    }
                                ?>
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
                    <h6> No announcement found. </h6>
            <?php
                }
            ?>
        </div> 
    </div>
</body>
</html>
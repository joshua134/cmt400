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
    $lecturerID = $_SESSION['lecID'];
    $lecturerDepartmentID = $_SESSION['lecDepId'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Home | CUEA Online Notice Board.</title>
    <link rel="stylesheet" href="../assets/css/lecturer/style.css" />
</head>
<body>
    <div class="header">
        <h5 class="logo">Welcome Mr/Mrs <?php echo $_SESSION['lecLastname'] ?>,</h5>
        <div class="nav">
            <a href="dashboard.php">Home</a>
            <a href="notice.php">Notice</a>
            <a href="announcement.php">Announcement</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
        <marquee scrolldelay="200"> You can only create notices & announcement related to your department </marquee>
    </div>
    <div class="container">
        <?php 
        
            $sql = "SELECT board.id, board.title, board.is_notice, board.is_announcement, board.departmentID as dID, 
                        board.created_at, board.updated_at, board.is_admin, board.is_lecturer, board.lecID, 
                        department.department, user.firstname, user.lastname
                        FROM board
                        LEFT JOIN user ON board.lecID = user.id
                        LEFT JOIN department ON board.departmentID = department.id ORDER BY created_at DESC";
            
            $result = mysqli_query($connection, $sql);
            if(mysqli_num_rows($result) > 0){
                while($row=mysqli_fetch_assoc($result)){
        ?>
                    <div class="box">
                        <span class='category'><p><?php if(isset($row['is_notice'])){ echo "Notice"; }else{ echo "Announcement"; } ?></p></span>
                        <span class='department'><p><?php if(isset($row['department'])){ echo $row['department']; }else{ echo 'General'; } ?> </p></span>
                        <span class='title'>
                            <a href="one.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
                        </span>
                        <span class='dates'><p><?php if(isset($row['updated_at'])){ echo "Updated At ".$row['updated_at'];  }else{ echo "Created At ".$row['created_at']; } ?></p></span>
                        <span class='creator'>
                            <p>
                                Create by : 
                                <?php 
                                    if((int) $row['is_admin'] == 1){
                                        echo "Admin";
                                    }else{
                                        if( (int) $row['lecID'] == $lecturerID ){
                                            echo 'You';
                                        }else{
                                            echo $row['firstname']." ".$row['lastname'];
                                        }
                                    }
                                ?>
                            </p>
                        </span>
                    </div>
                <?php } ?>
        <?php }else{ ?>
            <h3 class='nothing'>No notices or announcements found.</h3>
        <?php } ?>
    </div>
</body>
</html>
<?php
    include("./config/config.php");
    include("./config/functions.php");

    // start session
    session_start();
    if (!isset($_SESSION['loggedIn']) || !isset($_SESSION['userEmail']) || !isset($_SESSION['userID']) || !isset($_SESSION['departmentID'])) {
        // Redirect to index.php if any of the session variables are not set
        header("location: index.php");
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUEA Online Notice Board</title>
    <link rel="stylesheet" type="text/css" href="assets/css/home.css" />
</head>
<body>
    <div class="header">
        <h5 class="logo">CUEA Online Notice Board</h5>
        <div class="nav">
            <a href="home.php">Home</a>
            <a href="search.php">Search</a>
            <a href="contact.php">Contact Us</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <?php
            if(isset($_SESSION['departmentID'])){
                $userDepartment = $_SESSION['departmentID'];
                $sql= "SELECT board.id, board.title, board.is_notice, board.is_announcement, board.departmentID, board.created_at, board.updated_at AS dID FROM board
                    LEFT JOIN department ON board.departmentID = department.id 
                    WHERE board.departmentID IS NULL  OR board.departmentID = '".$userDepartment."' ORDER BY created_at DESC ";
            }else{
                $sql= "SELECT board.id, board.title, board.is_notice, board.is_announcement, board.departmentID AS dID, board.created_at, board.updated_at FROM board
                    LEFT JOIN department ON board.departmentID = department.id 
                    WHERE board.departmentID IS NULL ORDER BY created_at DESC ";
            }
            $result = mysqli_query($connection, $sql);
            if(mysqli_num_rows($result) > 0){
                while($data=mysqli_fetch_assoc($result)){
        ?>
                    <a class="box" href="<?php echo "board.php?id=".$data['id']; ?>">
                        <h4><?php if($data['is_notice']){ echo "Notice"; }else{ echo "Announcement"; } ?></h4>
                        <span><?php echo $data['title']; ?></span>
                        <p><?php if(isset($data['updated_at'])){ echo date("Y-m-d", strtotime($data['updated_at'])); }else{ echo date("Y-m-d", strtotime($data['created_at']));  } ?></p>
                    <a>
            <?php } ?>
        <?php } else{ ?>
            <p> No Announcement/Notice available. Check later.</p>
        <?php } ?>
    </div>
    <div class="footer">
        <!-- <div class="links">
            <h6>Links</h6>
            <a href="./admin">Login as Admin</a>
            <a href="./lecturer">Login as Lecturer</a>
        </div> -->
        <div class="copy">
            <p> &copy; Copyright 2024 CUEA. All Rights Reserved.
        </div>
    </div>
    <?php mysqli_close($connection); ?>
</body>
</html>
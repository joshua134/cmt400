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
    <title>Search | CUEA Online Notice Board</title>
    <link rel="stylesheet" href="assets/css/search.css" />
    <script src="assets/js/app.js"></script>
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
    <div class="container" onsubmit="return searchForm()">
        <form method="GET">
            <label for="search" >Search Notice/Announcement</label>
            <br/>
            <input type="text" name="search" id="search" placeholder="Search here ..." />
            <br/>
            <button type="submit">Search </button>
        </form>
        <div class="wrapper">
            <?php 
                if(isset($_GET['search'])){
                    $searchText = cleanInput($_GET['search']);
                    $searchText = mysqli_real_escape_string($connection, $searchText);
                    if(empty($searchText)){
                        echo "<script>alert('Please add some search text.');</script>";
                    }

                    $results = mysqli_query($connection, "SELECT * FROM board WHERE title LIKE '%$searchText%' OR content LIKE '%$searchText%' OR created_at LIKE '%$searchText%' OR updated_at LIKE '%$searchText%' ");

                    if(mysqli_num_rows($results)> 0){
            ?>
                        <h5 class="results"><?php echo mysqli_num_rows($results); ?> results found.</h5>
                        <div class="data">
            <?php
                        while($data = mysqli_fetch_assoc($results)){
                            
            ?>
                            <a class="box" href="<?php echo "board.php?id=".$data['id']; ?>">
                                <h4><?php if($data['is_notice']){ echo "Notice"; }else{ echo "Announcement"; } ?></h4>
                                <span><?php echo $data['title']; ?></span>
                                <p><?php if(isset($data['updated_at'])){ echo date("Y-m-d", strtotime($data['updated_at'])); }else{ echo date("Y-m-d", strtotime($data['created_at']));  } ?></p>
                            <a>
            <?php       
                        }
                    }else{
            ?>
                        <h5 class="results"> 0 results found.</h5>
            <?php
                    }
                }
            ?>
                </div>
        </div>
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
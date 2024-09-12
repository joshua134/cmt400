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
    <title>Project Statistics & Reports</title>
    <link rel="stylesheet" href="../assets/css/admin/reports.css" type="text/css"/>
    <script src="../assets/js/admin/app.js"></script>
</head>
<body>
    <div class="header">
        <a href="dashboard.php">Home</a>  / <a href="reports.php">Reports</a> / <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <div class="box">
            <h5>Number of Notices</h5>
            <?php
                $query1 = "SELECT COUNT(*) AS total_notices FROM board";
                $result = mysqli_query($connection, $query1);
                $row = mysqli_fetch_assoc($result);
                $total_notices = $row['total_notices'];
            ?>
            <p><?php echo $total_notices; ?></p>
        </div>
        <div class="box">
            <h5>Total Number of feedbacks</h5>
            <?php
                $query2 = "SELECT COUNT(*) AS total_feedback FROM feedback";
                $result = mysqli_query($connection, $query2);
                $row = mysqli_fetch_assoc($result);
                $total_feedback = $row['total_feedback'];
            ?>
            <p><?php echo $total_feedback; ?></p>
        </div>
        <div class="box" style="height: max-context;">
            <h5>Average Notices per department</h5>
            <?php
                $query3 = "SELECT departmentID, COUNT(*) / COUNT(DISTINCT departmentID) AS avg_notices_per_department FROM board GROUP BY departmentID";
                $result = mysqli_query($connection, $query3);
                while($row = mysqli_fetch_assoc($result)) {
            ?>
                <p>Department ID : <?php echo $row['departmentID'] ?> , Average Notice :  <?php echo $row['avg_notices_per_department']; ?> </p>
            <?php } ?>
        </div>
        <div class="box">
            <h5>Most commented notice</h5>
            <?php
                $query4 = "SELECT boardID, COUNT(*) AS num_comments FROM feedback GROUP BY boardID ORDER BY num_comments DESC";
                $result = mysqli_query($connection, $query4);
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_assoc($result);
            ?>
                    <p>ID : <?php echo $row['boardID']; ?> <br>  Number of Comments : <?php echo $row['num_comments']; ?></p>
            <?php } else{ ?>
                    <p>0</p>
            <?php } ?>
        </div>
    </div>
    <div class="reports">
        <h5>Project Reports</h5>
        <?php
            $query = "SELECT feedback.id as fID, feedback.is_blocked as fB, feedback.boardID, feedback.ip_addr, user.email, user.firstname,
                        user.lastname , board.id as bID, department.id as dID, department.department, feedback.created_at,
                        board.is_notice, board.is_announcement
                      FROM feedback
                      JOIN user ON feedback.userID = user.id
                      LEFT JOIN board ON feedback.boardID = board.id
                      LEFT JOIN department ON board.departmentID = department.id;";

            $results = mysqli_query($connection, $query);
            // count rows returned
            $rows = mysqli_num_rows($results);
            if($rows > 0){
        ?>
                <table>
                    <thead>
                        <tr>
                        <th>Ip Address</th>
                        <th>User Email</th>
                        <th>Department</th>
                        <th>Notice/Announcement Id</th>
                        <th>Is Notice ?</th>
                        <th>Is Announcement ?</th>
                        <th>Done At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($report = mysqli_fetch_assoc($results)){
                        ?>
                                <tr>
                                    <td><?php echo $report['ip_addr']; ?></td>
                                    <td><?php echo $report['email']; ?></td>
                                    <td>
                                        <?php
                                            if($report['department']){
                                                echo $report['department'];
                                            }else{
                                                echo "General Notice/Announcement";
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo $report['boardID']; ?></td>
                                    <td>
                                       <?php
                                        if($report['is_announcement']){
                                            echo "Yes";
                                        }else{ echo "No"; }
                                       ?>
                                    </td>
                                    <td>
                                        <?php
                                            if($report['is_announcement']){
                                                echo "Yes";
                                            }else{ echo "No"; }
                                        ?>
                                    </td>
                                    <td><?php echo $report['created_at']; ?></td>
                                </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
        <?php
            } else { echo "<h4>No data for reports found.</h4>"; }
        ?>
    </div>
</body>
</html>
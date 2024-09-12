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


    if(isset($_GET['messageID'])){
        $id = sanitizeNumberInput(cleanInput($_GET['messageID']));
        $query = "SELECT * FROM contact WHERE id = ".$id;
        $results = mysqli_query($connection, $query);

        if( mysqli_num_rows($results) < 1 ){
            header("Location: messages.php");
            exit();
        }

        $getRow = mysqli_fetch_assoc($results);
    }

    if(isset($_POST['deleteMessage'])){
        $messageID = sanitizeNumberInput(cleanInput($_POST['mID']));
        if( mysqli_query($connection, "DELETE FROM contact WHERE id = ".$messageID) ){
            echo "<script>alert('Message deleted successfully.');</script>";
            header("Refresh:0; url=messages.php");
            exit();
        }else{
            echo "Error: ". mysqli_error($connection);
             echo "<script>alert('Error in updating  notice.');</script>";
        }
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $getRow['contact_subject']; ?></title>
    <link rel="stylesheet" href="../assets/css/admin/create.css" type="text/css"/>
</head>
<body>
    <div class="header">
        <a href="dashboard.php">Home</a> / <a href="messages.php">Messages</a> / <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <div class="wrapper">
            <label >Sender</label><br/>
            <input type="text" readonly value="<?php  if (isset($getRow['contact_email'])){ echo $getRow['contact_email'];}else{ echo ""; } ?>" />
            <br/><br/>
            <label >Subject</label><br/>
            <input type="text" readonly value="<?php  if (isset($getRow['contact_subject'])){ echo $getRow['contact_subject'];}else{ echo ""; } ?>" />

            <br/><br/>
            <label >Sent On</label><br/>
            <input type="text" readonly value="<?php  if (isset($getRow['created_at'])){ echo $getRow['created_at'];}else{ echo ""; } ?>" />

            <br/><br/>
            <label >Message</label><br/>
            <textarea rows="10" cols="15"><?php if(isset($getRow['contact_message'])){ echo $getRow['contact_message']; } else { echo ""; } ?></textarea>
            

            <form method="post">
                <input type="hidden" name="mID" value="<?php echo $getRow['id']; ?>" />
                <button type="submit" name="deleteMessage">Delete Message<button>
            </form>
        </div>
    </div>
</body>
<?php  mysqli_close($connection); ?>
</html>

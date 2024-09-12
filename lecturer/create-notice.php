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
    $lecturerDepartmentID = $_SESSION['lecDepId'];

    $lectResult= mysqli_query($connection, "SELECT department FROM department WHERE id =".$lecturerDepartmentID);
    $lectData = mysqli_fetch_assoc($lectResult);

    if(isset($_POST['create'])){
        $title = cleanInput($_POST['title']);
        $content = cleanInput($_POST['content']);
        $image = $_FILES['image'];

        $title = sanitizeStringInput($title);
        $content = sanitizeStringInput($content);

        if(empty($title) || empty($content)){
            echo "<script>alert('Title and Content are required.');</script>";
        }else{
            $result = mysqli_query($connection, "SELECT title FROM board WHERE title = '$title' AND departmentID = $lecturerDepartmentID AND is_notice = true");
            // check if notice exists
            if( mysqli_num_rows($result) > 0 ){
                echo "<script>alert('Notice by title ".mysqli_fetch_assoc($result)['title']." already exists in the system.');</script>";
            }else{
                // check if imaage is available
                if(empty($_FILES['image']['name'])){
                    // no image
                    $sql1= "INSERT INTO board(title, content, is_notice, is_lecturer, lecID, departmentID) VALUES ('$title','$content', true, true, $lectureID, $lecturerDepartmentID)";
                    if(mysqli_query($connection, $sql1)){
                        echo "<script>alert('Notice added successfully.');</script>";
                    }else{
                        echo "Error: ". mysqli_error($connection);
                    }

                }else{
                    // if image present
                    $allowed_extensions = array("jpg","jpeg","png","gif");
                    // filename
                    $file_name = $_FILES['image']['name'];
                    $file_tmpName = $_FILES['image']['tmp_name'];

                    // $image_ext = explode(".", strtolower($file_name));
                    $image_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    if(!in_array($image_ext, $allowed_extensions)){
                        echo "<script>alert('Please upload a with PNG, JPEG, JPG, GIF extension.');</script>";
                    }else{
                        
                        $sql2= "INSERT INTO board(title, content, media, is_notice, is_lecturer, lecID, departmentID) VALUES ('$title','$content', '$file_name', true, true, $lectureID, $lecturerDepartmentID)";
                        if(mysqli_query($connection, $sql2)){
                            echo "<script>alert('Notice added successfully.');</script>";
                        }else{
                            echo "Error: ". mysqli_error($connection);
                        }

                        // move image to the directory.(tmp file, and folder/imagename)
                        move_uploaded_file($file_tmpName, "..//assets/images/notice/".$file_name);
                    }
                }
            }
        }
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Notice</title>
    <link rel="stylesheet" href="../assets/css/lecturer/create.css" type="text/css"/>
    <script src="../assets/js/admin/app.js"></script>
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
        <div class="wrapper">
            <h5>Create Notice</h5>
            <form method="post" action="create-notice.php" enctype="multipart/form-data" onsubmit="return createNotice()">
                <label for="title">Notice Title</label><br/>
                <input type="text" placeholder="Title" id="title" name="title" />
                <br/>
                <label for="content">Notice Content</label><br/>
                <textarea name="content" id="content" placeholder="Type your content here..." rows=5></textarea>
                <br/>
                <label for="department">Notice Department</label><br/>
                <input type="text" readonly='true' value="<?php echo $lectData['department']; ?>"/>
                <br/>
                <label for="image">Notice Image</label><br/>
                <input type="file" id="image" name="image" />
                <br/>
                <button type="submit" name="create">Create Notice</button>
            </form>
            
        </div>
    </div>
</body>
</html>
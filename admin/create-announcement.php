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


    if(isset($_POST['create'])){
        $title = cleanInput($_POST['title']);
        $content = cleanInput($_POST['content']);
        $department = cleanInput($_POST['department']);
        $image = $_FILES['image'];

        $title = sanitizeStringInput($title);
        $content = sanitizeStringInput($content);

        if(empty($title) || empty($content)){
            echo "<script>alert('Title and Content are required.');</script>";
        }else{
            
            $result = mysqli_query($connection, "SELECT title FROM board WHERE title = '$title' AND is_announcement = true");
            // check if announcement exists
            if( mysqli_num_rows($result) > 0 ){
                echo "<script>alert('Announcement by title ".mysqli_fetch_assoc($result)['title']." already exists in the system.');</script>";
            }else{
                if(empty($department)){
                    // announcement for all students.
                    // check if imaage is available
                    if(empty($_FILES['image']['name'])){
                        // no image
                        $sql1= "INSERT INTO board(title, content, is_announcement, is_admin) VALUES ('$title','$content', true, true)";
                        if(mysqli_query($connection, $sql1)){
                            echo "<script>alert('Announcement added successfully.');</script>";
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
                            
                            $sql2= "INSERT INTO board(title, content, media, is_announcement, is_admin) VALUES ('$title','$content', '$file_name', true, true)";
                            if(mysqli_query($connection, $sql2)){
                                echo "<script>alert('Announcement added successfully.');</script>";
                            }else{
                                echo "Error: ". mysqli_error($connection);
                            }

                            // move image to the directory.(tmp file, and folder/imagename)
                            move_uploaded_file($file_tmpName, "..//assets/images/announcement/".$file_name);
                        }
                    }
                }else{
                    // Announcement to student who belong to that department.
                    $department = (int)sanitizeNumberInput($department);
                    // check if imaage is available
                    if(empty($_FILES['image']['name'])){
                        // no image
                        $sql = "INSERT INTO board(title, content, departmentID, is_announcement, is_admin) VALUES ('$title','$content', '$department', true, true)";
                        if(mysqli_query($connection, $sql)){
                            echo "<script>alert('Announcement added successfully.');</script>";
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

                            $sql = "INSERT INTO board(title, content, media, departmentID, is_announcement, is_admin) VALUES ('$title','$content', '$file_name','$department', true, true)";
                            if(mysqli_query($connection, $sql)){
                                echo "<script>alert('Announcement added successfully.');</script>";
                            }else{
                                echo "Error: ". mysqli_error($connection);
                            }

                            // move image to the directory.(tmp file, and folder/imagename)
                            move_uploaded_file($file_tmpName, "../assets/images/announcement/".$file_name);
                        }
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
    <title>Create Announcement</title>
    <link rel="stylesheet" href="../assets/css/admin/create.css" type="text/css"/>
    <script src="../assets/js/admin/app.js"></script>
</head>
<body>
    <div class="header">
        <a href="dashboard.php">Home</a> / <a href="create-notice.php">Create Notice</a> / <a href="create-announcement.php">Create Announcement</a> / <a href="logout.php">Logout</a>
        <p style="text-align:center;"><marquee scrolldelay="200">Announcement created without department will be show to all users.</marquee><p>
    </div>
    <div class="container">
        <div class="wrapper">
            <h5>Create Announcement</h5>
            <form method="post" action="create-announcement.php" enctype="multipart/form-data" onsubmit="return createAnnouncement()">
                <label for="title">Announcement Title</label><br/>
                <input type="text" placeholder="Title" id="title" name="title" />
                <br/>
                <label for="content">Announcement Content</label><br/>
                <textarea name="content" id="content" placeholder="Type your content here..." rows=5></textarea>
                <br/>
                <?php 
                    $stmt = $connection->query("SELECT * FROM department");
                    // get all records as associative array
                    $departments = $stmt->fetch_all(MYSQLI_ASSOC);
                ?>
                <label for="department">Announcement Department</label><br/>
                <select id="department" name="department">
                    <option value="">Select department...</option>
                    <?php 
                        if(!empty($departments)){
                            // loop through the associative array and create options
                            foreach($departments as $department){
                                echo "<option value='{$department['id']}'>{$department['department']}</option>";
                            }
                        }else{
                            // no departments found.
                            echo "<option value='null'>No departments found.</option>";
                        }
                    ?>
                </select>
                <br/>
                <label for="image">Announcement Image</label><br/>
                <input type="file" id="image" name="image" />
                <br/>
                <button type="submit" name="create">Create Announcement</button>
            </form>
            
        </div>
    </div>
</body>
</html>
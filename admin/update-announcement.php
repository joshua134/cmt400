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


    if(isset($_GET['announcementID'])){
        $announcementID = cleanInput($_GET['announcementID']);
        $announcementID = sanitizeNumberInput($announcementID);

        $query = "SELECT  board.title, board.content, board.media, department.department, department.id as dID
                FROM board LEFT JOIN department ON board.departmentID = department.id WHERE board.id  = ".(int) $announcementID;

        $result = mysqli_query($connection, $query);
        $getRow = mysqli_fetch_assoc($result);
       
    }elseif(isset($_POST['update'])){
        $newTitle = trim($_POST['title']);
        $newContent = trim($_POST['content']);
        $newImage = $_FILES['image'];
        $oldImage = $_POST['existing_image'];
        $id = $_POST['announcementID'];
        $id = sanitizeNumberInput($id);

        $now = date('Y-m-d H:i:s');

        $old_Depart_ID;
        if(empty($_POST['oldDepartmentID'])){
            $old_Depart_ID = "null";
        }else{
            $old_Depart_ID = (int) $_POST['oldDepartmentID'];
        }
    

        $newDepartment;
        if(empty($_POST['department'])){
            $newDepartment = $old_Depart_ID;
        }else if( strcmp($_POST['department'], "null")  == 0 ){
            $newDepartment = "null";
        }else{
            $newDepartment = (int)trim($_POST['department']);
        }

        $newTitle = sanitizeStringInput($newTitle);
        $newContent = sanitizeStringInput($newContent);

        if(empty($newTitle) || empty($newContent)){
            echo "<script>alert('Title and Content are required.');</script>";
        }else{
            // check department 
            // same department
            if( $old_Depart_ID == $newDepartment ){
                // check image now if empty or not
                if(empty($newImage['name'])){
                    // image empty now we insert data
                    // changes title content only

                    $sql = "UPDATE board SET title = '".$newTitle."', content='".$newContent."', updated_at='".$now."' WHERE id = '".$id."' AND is_announcement = true";
                    if(mysqli_query($connection, $sql)){
                        echo "<script>alert('Announcement updated successfully.');</script>";
                    }else{
                        echo "Error: ". mysqli_error($connection);
                        echo "<script>alert('Error in updating  Announcement.');</script>";
                    }
                    // header("Location: dashboard.php");
                    // exit();
                }else{
                    // image not empty we process it
                    $allowed_extensions = array("jpg","png", "jpeg", "gif");
                    $newImage_ext = strtolower(pathinfo($newImage['name'], PATHINFO_EXTENSION));
                    $newImage_tmpFile = $newImage['tmp_name'];

                    // check if image
                    if(!in_array($newImage_ext, $allowed_extensions)){
                        echo "<script>alert('Please upload a with PNG, JPEG, JPG, GIF extension.');</script>";
                    }else{
                        // query
                        // department remain same
                        // image changes
                        // title, content, changes

                        $sql = "UPDATE board SET title = '".$newTitle."', content='".$newContent."', media='".$newImage['name']."', updated_at='".$now."' WHERE id = '".$id."' AND is_announcement = true";
                        if(mysqli_query($connection, $sql)){
                            
                            if(empty($oldImage)){
                                // move image to the directory.(tmp file, and folder/imagename)
                               move_uploaded_file($newImage_tmpDir, "../assets/images/notice/".$newImage['name']);
                            }else{
                                $pathToOldImage ="../assets/images/notice/{$oldImage}"; 
                                if(file_exists($pathToOldImage)){
                                    // delete it 
                                    unlink($pathToOldImage);
                                }
    
                                // move image to the directory.(tmp file, and folder/imagename)
                                move_uploaded_file($newImage_tmpDir, "../assets/images/notice/".$newImage['name']);
                            }

                            echo "<script>alert('Notice updated successfully.');</script>";
                        }else{
                            echo "Error: ". mysqli_error($connection);
                            echo "<script>alert('Error in updating  notice.');</script>";
                        }
                    }
                }
            }else{
                // different department
                // check image now if empty or not
                if(empty($newImage['name'])){
                    // image empty now we insert data
                    // changes title content only

                    $sql = "UPDATE board SET title = '".$newTitle."', content='".$newContent."', updated_at='".$now."', departmentID='".$newDepartment."' WHERE id = '".$id."' AND is_announcement = true";
                    if(mysqli_query($connection, $sql)){
                        echo "<script>alert('Notice updated successfully.');</script>";
                    }else{
                        echo "Error: ". mysqli_error($connection);
                        echo "<script>alert('Error in updating  notice.');</script>";
                    }
                    // header("Location: dashboard.php");
                    // exit();
                }else{
                    // image found, process it
                    // image not empty we process it
                    $allowed_extensions = array("jpg","png", "jpeg", "gif");
                    $newImage_ext = strtolower(pathinfo($newImage['name'], PATHINFO_EXTENSION));
                    $newImage_tmpFile = $newImage['tmp_name'];

                    // check if image
                    if(!in_array($newImage_ext, $allowed_extensions)){
                        echo "<script>alert('Please upload a with PNG, JPEG, JPG, GIF extension.');</script>";
                    }else{
                        // query
                        // department remain same
                        // image changes
                        // title, content, changes

                        $sql = "UPDATE board SET title = '".$newTitle."', content='".$newContent."', media='".$newImage['name']."', departmentID='".$newDepartment."',updated_at='".$now."' WHERE id = '".$id."' AND is_announcement = true";
                        if(mysqli_query($connection, $sql)){
                            
                            if(empty($oldImage)){
                                // move image to the directory.(tmp file, and folder/imagename)
                               move_uploaded_file($newImage_tmpDir, "../assets/images/notice/".$newImage['name']);
                            }else{
                                $pathToOldImage ="../assets/images/notice/{$oldImage}"; 
                                if(file_exists($pathToOldImage)){
                                    // delete it 
                                    unlink($pathToOldImage);
                                }
    
                                // move image to the directory.(tmp file, and folder/imagename)
                                move_uploaded_file($newImage_tmpDir, "../assets/images/notice/".$newImage['name']);
                            }

                            echo "<script>alert('Notice updated successfully.');</script>";
                        }else{
                            echo "Error: ". mysqli_error($connection);
                            echo "<script>alert('Error in updating  notice.');</script>";
                        }
                    }
                }
            }
        }

    }else{
        // no other methods permitted.
        // return user to home admin page.
        header("Location: dashboard.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Notice</title>
    <link rel="stylesheet" href="../assets/css/admin/create.css" type="text/css"/>
    <script src="../assets/js/admin/app.js"></script>
</head>
<body>
    <div class="header">
        <a href="dashboard.php">Home</a> / <a href="update-announcement.php">Update Announcement</a> / <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <div class="wrapper">
            <h5>Update Department</h5>
            <form method="post" action="update-notice.php" enctype="multipart/form-data"  onsubmit="return noticeUpdate()">
                <label for="title">Announcement Title</label><br/>
                <input type="text" placeholder="Title" id="title" name="title" value="<?php  if (isset($getRow['title'])){ echo $getRow['title'];}else{ echo ""; } ?>" />
                <br/>
                <label for="content">Announcement Content</label><br/>
                <textarea name="content" id="content" placeholder="Type your content here..." rows=5><?php  if (isset($getRow['content'])){ echo $getRow['content'];}else{ echo ""; } ?></textarea>
                <br/>
                <input type="hidden" name="noticeID" value="<?php echo $noticeID; ?>" />
                <?php
                    $stmt = $connection->query("SELECT * FROM department");
                    // get all records as an associative array
                    $departments = $stmt->fetch_all(MYSQLI_ASSOC);

                    $result = mysqli_query($connection, "SELECT * FROM department");
                ?>
                <label for="department">Announcement Department</label><br/>
                <input type="hidden" name="oldDepartmentID" value="<?php echo $getRow['dID']; ?>" />
                <input type="text" name="oldNoticeDepartment" readonly value="<?php  if (isset($getRow['department'])){ echo $getRow['department'];}else{ echo "No department"; } ?>"/>
                <select id="department" name="department" style="margin:6px 0;">
                    <option value="">Select new department...</option>
                    <option value="null">Announcement to all students.</option>
                    <?php 
                        if(mysqli_num_rows($result) > 0){
                            // loop through the associative array and create options
                            while($departmentRow = mysqli_fetch_assoc($result)){
                    ?>
                                <option value='<?php echo $departmentRow['id']; ?>'><?php echo $departmentRow['department']; ?></option>";
                    <?php
                            }
                        }else{
                    ?>
                            <option value='null'>No departments found.</option>
                    <?php  }
                    ?>
                </select>
                <label for="image">Announcement Image</label><br/>
                <?php
                    // Display the existing image if available
                    if (!empty($getRow['media'])) {
                ?>
                    <img src="../assets/images/notice/<?php echo $getRow['media']; ?>" style='width:100px;height:100px;' alt="image" />
                    <input type='hidden' name='existing_image' value='<?php echo $getRow['media']; ?>' />
                <?php }else{ ?>
                    <img src=""  alt="No image" />
                    <input type='hidden' name='existing_image' value='<?php echo $getRow['media']; ?>' />
                <?php } ?>
                <input type="file" id="image" name="image" />
                <br/>
                <button type="submit" name="update">Update Announcement</button>
            </form>
            
        </div>
    </div>
    <?php  mysqli_close($connection); ?>
</body>
</html>
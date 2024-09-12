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

    if(isset($_GET['noticeID'])){
        $noticeID = trim($_GET['noticeID']);
        $noticeID = sanitizeNumberInput($noticeID);

        $query = "SELECT  board.title, board.content, board.media, department.department, department.id as dID
                FROM board LEFT JOIN department ON board.departmentID = department.id WHERE board.id  = ".(int) $noticeID;

        $result = mysqli_query($connection, $query);
        $getRow = mysqli_fetch_assoc($result);
       
    }elseif(isset($_POST['update'])){
        $newTitle = trim($_POST['title']);
        $newContent = trim($_POST['content']);
        $newImage = $_FILES['image'];
        $oldImage = $_POST['existing_image'];
        $id = $_POST['noticeID'];
        $id = sanitizeNumberInput($id);

        $now = date('Y-m-d H:i:s');

        $newTitle = sanitizeStringInput($newTitle);
        $newContent = sanitizeStringInput($newContent);

        if(empty($newTitle) || empty($newContent)){
            echo "<script>alert('Title and Content are required.');</script>";
        }else{
            // check if image is added
            if(empty($newImage['name'])){
                // image empty now we insert data
                // changes title content only

                $sql = "UPDATE board SET title = '".$newTitle."', content='".$newContent."', updated_at='".$now."' WHERE id = '".$id."' AND is_notice = true";
                if(mysqli_query($connection, $sql)){
                    echo "<script>alert('Notice updated successfully.');</script>";
                    header("Refresh: 0; url=notice.php");
                    exit();
                }else{
                    echo "Error: ". mysqli_error($connection);
                    echo "<script>alert('Error in updating  notice.');</script>";
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

                    $sql = "UPDATE board SET title = '".$newTitle."', content='".$newContent."', media='".$newImage['name']."', updated_at='".$now."' WHERE id = '".$id."' AND is_notice = true";
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
                        header("Refresh: 0; url=notice.php");
                        exit();
                    }else{
                        echo "Error: ". mysqli_error($connection);
                        echo "<script>alert('Error in updating  notice.');</script>";
                    }
                    // header("Location: dashboard.php");
                    // exit();
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
            <h5>Update Notice</h5>
            <form method="post" action="update-notice.php" enctype="multipart/form-data"  onsubmit="return noticeUpdate()">
                <label for="title">Notice Title</label><br/>
                <input type="text" placeholder="Title" id="title" name="title" value="<?php  if (isset($getRow['title'])){ echo $getRow['title'];}else{ echo ""; } ?>" />
                <br/>
                <label for="content">Notice Content</label><br/>
                <textarea name="content" id="content" placeholder="Type your content here..." rows=5><?php  if (isset($getRow['content'])){ echo $getRow['content'];}else{ echo ""; } ?></textarea>
                <br/>
                <input type="hidden" name="noticeID" value="<?php echo $noticeID; ?>" />
                
                <label for="department">Notice Department</label><br/>
                <input type="text" name="department" readonly value="<?php  if (isset($getRow['department'])){ echo $getRow['department'];}else{ echo "No department"; } ?>"/>
                <label for="image">Notice Image</label><br/>
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
                <button type="submit" name="update">Update Notice</button>
            </form>
            
        </div>
    </div>
    <?php  mysqli_close($connection); ?>
</body>
</html>

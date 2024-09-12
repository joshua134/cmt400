<?php
    include("../config/config.php");
    include("../config/functions.php");

    // start session
    session_start();

    /// check if user is logged in and we have a user id
    if( empty($_SESSION['lecturerLoggedIn']) || empty($_SESSION['lecID'])  || empty($_SESSION['lecEmail']) || empty($_SESSION['lecLastname']) || empty($_SESSION['lecDepId']) ){
        // if false return user to login page
        header("Location: index.php");
        // prevent further executions.
        exit();
    }

    $lectureID = $_SESSION['lecID'];
    $lecturerDepartmentID = $_SESSION['lecDepId'];
    $lecturerEmail = $_SESSION['lecEmail'];

    $q = "SELECT  user.firstname, user.lastname, user.email, user.password, department.id AS DID, department.department FROM user
            JOIN department ON user.departmentID = department.id 
            WHERE user.id = $lectureID AND email = '$lecturerEmail' ";

    $result = mysqli_query($connection, $q);
    $getRow = mysqli_fetch_assoc($result);

    $passwordPatternRegex = '/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@!&\*\(\)\-+_\=<>]).{6,}$/';
    $specialChars = "@ ! & * - + _  < > ";

    if(isset($_POST['profile'])){
        $firstname = htmlspecialchars(sanitizeStringInput(cleanInput($_POST['firstname'])));
        $lastname = htmlspecialchars(sanitizeStringInput(cleanInput($_POST['lastname'])));
        $password = htmlspecialchars(sanitizeStringInput(cleanInput($_POST['password'])));
        $cpassword = htmlspecialchars(sanitizeStringInput(cleanInput($_POST['cpassword'])));
        $oldDepart = $_POST['oldDepartmentID'];
        $department = $_POST['department'];

        if(empty($firstname) || empty($lastname)){
            echo "<script>alert('Name cannot be empty.');</script>";
        }else{
            // not same department
            if(strcmp($department, $oldDepart) ==  1 ){
                // check if password remains or be it changed
                // password remains
                if(empty($password) && empty($cpassword) ){
                    $query = "UPDATE user SET firstname='$firstname', lastname='$lastname', departmentID=$department WHERE id = $lectureID AND email = '$lecturerEmail' ";
                    if(mysqli_query($connection, $query)){
                        echo "<script>alert('Account updated successfully.');</script>";
                    }else{
                        echo "Error : ".mysqli_error($connection);
                    }
                }else{
                    // password changed
                    if(!preg_match($passwordPatternRegex, $password) || !preg_match($passwordPatternRegex, $cpassword) ){
                        echo "<script>alert('Password must have atleast one uppercase, small case letter, a number, atleast one one of : $specialChars and it cannot be less than 6 characters.');</script>";
                    } else if (strcmp($password, $cpassword) ==1 ){
                        echo "<script>alert('Password do not match.');</script>";
                    }else{
                        $hashedPassword = md5($password);
                        $query = "UPDATE user SET firstname='$firstname', lastname='$lastname', departmentID=$department, password = '$hashedPassword' WHERE id = $lectureID AND email = '$lecturerEmail' ";
                        if(mysqli_query($connection, $query)){
                            echo "<script>alert('Account updated successfully.');</script>";
                        }else{
                            echo "Error : ".mysqli_error($connection);
                        }
                    }
                }
            }else{
                // same department
                // check if password remains or be it changed
                // password remains
                if(empty($password)){
                    $query = "UPDATE user SET firstname='$firstname', lastname='$lastname' WHERE id = $lectureID AND email = '$lecturerEmail' ";
                    if(mysqli_query($connection, $query)){
                        echo "<script>alert('Account updated successfully.');</script>";
                    }else{
                        echo "Error : ".mysqli_error($connection);
                    }
                }else{
                    // password changed
                    if(!preg_match($passwordPatternRegex, $password) || !preg_match($passwordPatternRegex, $cpassword) ){
                        echo "<script>alert('Password must have atleast one uppercase, small case letter, a number, atleast one one of : $specialChars and it cannot be less than 6 characters.');</script>";
                    } else if (strcmp($password, $cpassword) ==1 ){
                        echo "<script>alert('Password do not match.');</script>";
                    }else{
                        $hashedPassword = md5($password);
                        $query = "UPDATE user SET firstname='$firstname', lastname='$lastname', password='$hashedPassword' WHERE id = $lectureID AND email = '$lecturerEmail' ";
                        if(mysqli_query($connection, $query)){
                            echo "<script>alert('Account updated successfully.');</script>";
                        }else{
                            echo "Error : ".mysqli_error($connection);
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
    <title>Lecturer <?php echo $getRow['lastname']; ?>'s Profile</title>
    <link rel="stylesheet" href="../assets/css/lecturer/profile.css" />
    <script src="../assets/js/lecturer/app.js"></script>
</head>
<body>
    <div class="header">
        <h5 class="logo">Welcome Mr/Mrs <?php echo $_SESSION['lecLastname'] ?>,</h5>
        <div class="nav">
            <a href="dashboard.php">Home</a>
            <a href="announcement.php">Announcement</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <form method="post" onsubmit="return checkUpdateForm()">
            <label for="firstname">Firstname</label><br/>
            <input type="text" name="firstname" id="firstname" value="<?php echo $getRow['firstname']; ?>" />
            <br/>
            <label for="lastname">Lastname</label><br/>
            <input type="text" name="lastname" id="lastname" value="<?php echo $getRow['lastname']; ?>" />
            <br/>
            <label for="email" >Email Address</label><br/>
            <input type="email" readonly="true" value="<?php echo $getRow['email']; ?>" />
            <br/>
            <label>Current Department</label><br/>
            <input type="text" readonly="true" value="<?php echo $getRow['department']; ?>" />
            <input type="hidden" name="oldDepartmentID" value="<?php echo $getRow['DID'] ?>" />
            <br/>
            <?php 
                    $stmt = $connection->query("SELECT * FROM department");
                    // get all records as associative array
                    $departments = $stmt->fetch_all(MYSQLI_ASSOC);
                ?>
                <label for="department">New Department</label>
                <select id="department" name="department">
                    <option value="">Select new department...</option>
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
            <label for="password">Password</label><br/>
            <div class="input-group">
                <input type="password" placeholder="Password" name="password" id="password" />
                <span id="span-pwd" onclick="showPassword()">Show</span>
            </div>
            <label for="cpassword">Confirm Password</label>    <br/>
            <div class="input-group">
                <input type="password" placeholder="Confirm Password" name="cpassword" id="cpassword" />
                <span id="span-cpwd" onclick="showCPassword()">Show</span>
            </div>
            <br/>
            <button type="submit" name="profile">Update Profile </button>
        </form>            
    </div>
</body>
</html>
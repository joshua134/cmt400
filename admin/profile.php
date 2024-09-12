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


    $adminID = $_SESSION['adminID'];
    $adminMail = $_SESSION['adminEmail'];

    $q = "SELECT  firstname, lastname, email, password  FROM user WHERE id = $adminID AND email = '$adminMail' ";

    $result = mysqli_query($connection, $q);
    $getRow = mysqli_fetch_assoc($result);

    $passwordPatternRegex = '/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@!&\*\(\)\-+_\=<>]).{6,}$/';
    $specialChars = "@ ! & * - + _  < > ";

    if(isset($_POST['profile'])){
        $firstname = sanitizeStringInput(cleanInput($_POST['firstname']));
        $lastname = sanitizeStringInput(cleanInput($_POST['lastname']));
        $password = sanitizeStringInput(cleanInput($_POST['password']));
        $cpassword = sanitizeStringInput(cleanInput($_POST['cpassword']));

        if(empty($firstname) || empty($lastname) ){
            echo "<script>alert('Firstname and Lastname are required.');</script>";
        }else{
            // if password is empty retain
            if(empty($password)){
                if(mysqli_query($connection, "UPDATE user SET firstname='$firstname', lastname='$lastname' WHERE id=$adminID AND email = '$adminMail' ")){
                    echo "<script>alert('Account updated successfully.');</script>";
                }else{
                    echo " Error ".mysqli_error($connection);
                }
            }else{  
                if(!preg_match($passwordPatternRegex, $password) || !preg_match($passwordPatternRegex, $cpassword) ){
                    echo "<script>alert('Password must have atleast one uppercase, small case letter, a number, atleast one one of : $specialChars and it cannot be less than 6 characters.');</script>";
                } else if (strcmp($password, $cpassword) ==1 ){
                    echo "<script>alert('Password do not match.');</script>";
                }else{
                    $hashedPassword = md5($password);
                    if(mysqli_query($connection, "UPDATE user SET firstname='$firstname', lastname='$lastname', password='$hashedPassword' WHERE id=$adminID AND email = '$adminMail' ")){
                        echo "<script>alert('Account updated successfully.');</script>";
                    }else{
                        echo " Error ".mysqli_error($connection);
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
    <title>Update Profile</title>
    <link rel="stylesheet" href="../assets/css/admin/profile.css" />
    <script src="../assets/js/admin/app.js"></script>
</head>
<body>
    <div class="header">
        <a href="dashboard.php">Home</a> / <a href="profile.php">Update Profile</a> / <a href="logout.php">Logout</a>
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
    <?php mysqli_close($connection); ?>
</body>
</html>
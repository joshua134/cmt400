<?php 
    include("./config/config.php");
    include("./config/functions.php");
    session_start();

    if(empty($_SESSION['passwordResetEmail'])){
        header("Location: forgot-password.php");
        exit();
    }

    // check password has atleast 6 characters of atleast one uppercase , one small case and number
    $pwdRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6, }/$';

    $passwordPatternRegex = '/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@!&\*\(\)\-+_\=<>]).{6,}$/';


    if(isset($_POST['reset'])){

        $code = cleanInput($_POST['code']);
        $password = cleanInput($_POST['password']);
        $cPassword = cleanInput($_POST['cpassword']);
        $specialChars = "@ ! & * ( ) - + _ = < > ";

        $code = sanitizeNumberInput($code);
        $password = sanitizeStringInput($password);
        $cPassword = sanitizeStringInput($cPassword);

        // if( preg_match($passwordPatternReg  ex, $password) )
        if(empty($code) || empty($password) || empty($cPassword) ){
            echo "<script>alert('All fields are required.');</script>";
        } else if(!preg_match($passwordPatternRegex, $password) || !preg_match($passwordPatternRegex, $cPassword)){
            echo "<script>alert('Password must have atleast one uppercase, small case letter, a number, atleast one one of : $specialChars and it cannot be less than 6 characters.');</script>";
        }else if(strcmp($password, $cPassword) == 1){
            echo "<script>alert('Passwords do not match.');</script>";
        }else{
            $hashedPassword = md5($password);
            $email = $_SESSION['passwordResetEmail'];
            $now = date('Y-m-d H:i:s');
            $result = mysqli_query($connection, "SELECT id, email FROM user WHERE email = '$email' AND password_reset_code = ".$code);
            if(mysqli_num_rows($result) > 0){
                $query = "UPDATE user SET password = '$hashedPassword', password_reset_at = '$now', password_reset_code = null WHERE email = '$email' ";
                if(mysqli_query($connection, $query)){
                    echo "<script>alert('Password changed successfully.');</script>";
                    unset($_SESSION["passwordResetEmail"]);
                    header("Refresh:0; url=index.php");
                    // prevent further executions
                    exit();
                }else{
                    echo "Error :".mysqli_error($connection);
                }
            }else{
                echo "<script>alert('Invalid password reset code.');</script>";
            }
        }
    }

    if(isset($_POST['resend'])){
        $password_reset_code = rand(100000, 999999);
        $email = $_SESSION['passwordResetEmail'];
        if(mysqli_query($connection, "UPDATE user SET password_reset_code = $password_reset_code WHERE email = '$email' ")){
            echo "<script>alert('A new password reset code has been sent to your email. Please check your INBOX/SPAM folder.');</script>";
        }else{
            echo "Error: ". mysqli_error($connection);
        }
    }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset | CUEA Online Notice Board</title>
    <link href="assets/css/reset-password.css" rel="stylesheet" /> 
    <script src="assets/js/app.js"></script>
</head>
<body>
    <div class="header">
        <h5 class="logo">CUEA Online Notice Board</h5>
        <div class="nav">
            <a href="index.php">Sign In</a>
            <a href="contact.php">Contact Us</a>
        </div>
    </div>
    <div class="container">
        <div class="wrapper">
            <h5>Reset Account Password</h5>
            <form method="post" onsubmit="return passwordReset()" >
                <label for="code">Reset Code</label>
                <br/>
                <input type="number" placeholder="0123456" name="code" id="code" value="<?php if(isset($code)){ echo $code; } ?>"/>
                <label for="password">Password</label>
                <br/>
                <div class="input-group">
                    <input type="password" placeholder="Password" name="password" id="password" />
                    <span id="span-pwd" onclick="showPassword()">Show</span>
                </div>
                <label for="cpassword">Confirm Password</label>
                <br/>
                <div class="input-group">
                    <input type="password" placeholder="Confirm Password" name="cpassword" id="cpassword" />
                    <span id="span-cpwd" onclick="showCPassword()">Show</span>
                </div>
                <br/>
                <button type="submit" name="reset">Reset Password</button>
            </form>
            <form method="post">
                <button name="resend" type="submit">Resend Password Reset Code</button>
            </form>
        </div>
    </div>
    <?php mysqli_close($connection); ?>
    <div class="footer">
        <div class="links">
            <h6>Links</h6>
            <a href="./admin">Login as Admin</a>
            <a href="./lecturer">Login as Lecturer</a>
        </div>
        <div class="copy">
            <p> &copy; Copyright 2024 CUEA. All Rights Reserved.
        </div>
    </div>
</body>
</html>
<?php 
    include("../config/config.php");
    include("../config/functions.php");

    session_start();

    if(!isset($_SESSION['adminPasswordResetEmail'])){
        header("Location: forgot.php");
        exit();
    }

    $passwordPatternRegex = '/^[a-zA-Z0-9@!&*()-+_=<>]{6,}$/i';


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
            echo "<script>alert('$specialChars are the only special characters allowed and password cannot be less than 6 characters.');</script>";
        }else if(strcmp($password, $cPassword) == 1){
            echo "<script>alert('Passwords do not match.');</script>";
        }else{
            $hashedPassword = md5($password);
            $email = $_SESSION['adminPasswordResetEmail'];
            $now = date('Y-m-d H:i:s');
            $result = mysqli_query($connection, "SELECT id, email FROM user WHERE email = '$email' AND password_reset_code = ".$code);
            if(mysqli_num_rows($result)> 0){
                $q = "UPDATE user SET password_reset_code = null, password = '$hashedPassword', password_reset_at = '$now' WHERE email = '$email' ";
                if(mysqli_query($connection, $q)){
                    echo "<script>alert('Password changed successfully.');</script>";
                    unset($_SESSION["adminPasswordResetEmail"]);
                    header("Location: index.php");
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
        $email = $_SESSION['adminPasswordResetEmail'];
        if(mysqli_query($connection, "UPDATE user SET password_reset_code = '$password_reset_code' WHERE email = '$email' ")){
            echo "<script>alert('A password reset code has been sent to your email. Please check your INBOX/SPAM folder.');</script>";
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
    <title>Admin Password Reset | CUEA Online Notice Board</title>
    <link href="../assets/css/reset-password.css" rel="stylesheet" /> 
    <script src="../assets/js/app.js"></script>
</head>
<body>
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
</body>
</html>
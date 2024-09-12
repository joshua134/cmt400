<?php 
    include("./config/config.php");
    include("./config/functions.php");

    if(isset($_POST['checkaccount'])){
        $email = cleanInput($_POST['email']);
        $email = sanitizeEmailInput($email);

        if(empty($email)){
            echo "<script>alert('An email address is required.');</script>";
        } else {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo "<script>alert('Please use a valid email address.');</script>";
            }else{
                $query = "SELECT * FROM user WHERE email = '$email' LIMIT 1";
                $result = mysqli_query($connection, $query);

                if( mysqli_num_rows($result) < 1 ){
                    echo "<script>alert('Account associated with this email $email not found.');</script>";
                }else{
                    // check if user is blocked or not activated show message
                    $data = mysqli_fetch_assoc($result);

                    // is_activated, is_blocked
                    if((bool) $data['is_blocked']){
                        echo "<script>alert('Your account is blocked, please contact the admin for assistance.');</script>";
                        header("Refresh: 0; url=contact.php");
                        // prevent further executions
                        exit();
                    }else if( (bool) $data['is_activated'] == false ) {
                        session_start();
                        $_SESSION['registerEmail'] = $email;
                        echo "<script>alert('You need to activate you account.');</script>";
                        // redirect to the activate page
                        header("Refresh: 0; url=activate.php");
                        // prevent further executions
                        exit();
                    }else{
                        $password_reset_code = rand(100000, 999999);
                        session_start();
                        $_SESSION['passwordResetEmail'] = $email;
                        if(mysqli_query($connection, "UPDATE user SET password_reset_code = $password_reset_code WHERE email = '$email'")){
                            echo "<script>alert('A password reset code has been sent to your email. Please check your INBOX/SPAM folder.');</script>";
                            header("Refresh: 0; url=reset-password.php");
                            // prevent further executions
                            exit();
                        }else{
                            echo "Error: ". mysqli_error($connection);
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
    <title>Forgot Password | CUEA Online Notice Board</title>
    <link href="assets/css/forgot.css" rel="stylesheet" /> 
    <script src="assets/js/app.js"></script>
</head>
<body>
    <div class="header">
        <h5 class="logo">CUEA Online Notice Board</h5>
        <div class="nav">
            <a href="index.php">Sign In</a>
            <a href="register.php">Sign Up</a>
            <a href="contact.php">Contact Us</a>
        </div>
    </div>
    <div class="container">
        <div class="wrapper">
            <h5>Check Account</h5>
            <form method="post" onsubmit="return checkAccount()" >
                <label for="email">Email Address</label>
                <br/>
                <input type="email" placeholder="someone@m.com" name="email" id="email" value="<?php if(isset($email)){ echo $email; } ?>"/>
                <br/>
                <button type="submit" name="checkaccount">Check Account</button>
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
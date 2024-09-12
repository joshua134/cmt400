<?php 
    include("../config/config.php");
    include("../config/functions.php");

    if(isset($_POST['checkaccount'])){
        $email = cleanInput($_POST['email']);
        $email = sanitizeEmailInput($email);

        if(empty($email)){
            echo "<script>alert('An email address is required.');</script>";
        } else {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo "<script>alert('Please use a valid email address.');</script>";
            }else{
                $query = "SELECT * FROM user WHERE email = '$email' AND is_admin = true LIMIT 1";
                $result = mysqli_query($connection, $query);

                if( mysqli_num_rows($result) < 1 ){
                    echo "<script>alert('Account associated with this email $email not found.');</script>";
                }else{
                    $password_reset_code = rand(100000, 999999);
                    session_start();
                    $_SESSION['adminPasswordResetEmail'] = $email;
                    if(mysqli_query($connection, "UPDATE user SET password_reset_code = '$password_reset_code' WHERE email = '$email' AND is_admin = true ")){
                        echo "<script>alert('A password reset code has been sent to your email. Please check your INBOX/SPAM folder.');</script>";
                        header("Location: reset.php");
                    }else{
                        echo "Error: ". mysqli_error($connection);
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
    <title>Admin Forgot Password | CUEA Online Notice Board</title>
    <link href="../assets/css/forgot.css" rel="stylesheet" /> 
    <script src="../assets/js/app.js"></script>
</head>
<body>
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
</body>
</html>
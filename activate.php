<?php 
    include("./config/config.php");
    include("./config/functions.php");

    session_start();

    if(empty($_SESSION['registerEmail'])){
        header("Location: index.php");
        exit();
    }

    if(isset($_POST['activate'])){
        $code = sanitizeNumberInput(cleanInput($_POST['code']));

        if(empty($code)){
            echo "<script>alert('Activation code is required.');</script>";
        }else{
            $email = $_SESSION['registerEmail'];
            $now = date('Y-m-d H:i:s');

            $result = mysqli_query($connection, "SELECT * FROM user WHERE email  = '$email' AND activation_code = ".$code);
            if(mysqli_num_rows($result) > 0){
                $data = mysqli_fetch_assoc($result);
                if($data['is_activated']){
                    echo "<script>alert('You account is already activated.');</script>";
                }else{
                    if(mysqli_query($connection, "UPDATE user SET is_activated = true, activated_at = '$now', activation_code = null WHERE email = '$email' ")){
                        echo "<script>alert('Account activated successfully.');</script>";
                        unset($_SESSION['registerEmail']);
                        header("Refresh: 0; url=index.php");
                    }else{
                        echo "Error : ".mysqli_error($connection);
                    }
                }
            }else{
                echo "<script>alert('Invalid activation code.');</script>";
            }
        }
    }

    if(isset($_POST['resend'])){
        $activation_code = rand(100000, 999999);
        if(mysqli_query($connection, "UPDATE user SET activation_code = '$activation_code' WHERE email = ".$_SESSION['registerEmail'])){
            $message = "An new account activation code has been sent to your email. Please check your INBOX/SPAM folder.";
            echo "<script>alert('$message');</script>";
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
    <title>Activate Account | CUEA Online Notice Board</title>
    <link href="assets/css/activate.css" rel="stylesheet" /> 
    <script src="assets/script/app.js"></script>
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
            <h5>Activate Account</h5>
            <form method="post" onsubmit="return activateAccount()" >
                <label for="code">Activation Code</label>
                <br/>
                <input type="number" placeholder="0123456" name="code" id="code" value="<?php if(isset($code)){ echo $code; } ?>"/>
                <br/>
                <button type="submit" name="activate">Activate Account</button>
            </form>
            <form method="post">
                <button name="resend" type="submit">Resend Activation Code</button>
            </form>
        </div>
    </div>
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
    <?php mysqli_close($connection); ?>
</body>
</html>
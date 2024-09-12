<?php
    include("./config/config.php");
    include("./config/functions.php");

    if(isset($_POST['login'])){
        // using trim() to remove white spaces(blanks) from input 
        $email = cleanInput($_POST['email']);
        $password = cleanInput($_POST['password']);

        // sanitize email
        $email = mysqli_real_escape_string($connection, sanitizeEmailInput($email));
        // sanitize password
        $password = mysqli_real_escape_string($connection, sanitizeStringInput($password));
        
        if(empty($email) || empty($password)){
            echo "<script>alert('All fields are required.');</script>";
        }else{
            // if email address does not have a valid format show message
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo "<script>alert('Please use a valid email address.');</script>";
            }else{
                $lQuery = "SELECT id, is_normal, is_activated, is_blocked, password, departmentID as dID FROM user WHERE email = '$email'";
                $result = mysqli_query($connection, $lQuery);

                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_assoc($result);
                    if(password_verify($password, $row['password'])){
                        session_start();
                        // check if account is activated
                        if( (bool) $row['is_activated'] && ! (bool) $row['is_blocked'] ){
                            // store user information to session variable
                            $_SESSION['loggedIn'] = true;
                            $_SESSION['userEmail'] = $email;
                            $_SESSION['userID'] = $row['id'];
                            $_SESSION['departmentID'] = $row['dID'];
                            
                            echo "<script>alert('You have successfully signed in.');</script>";
                            // redirect user to home page
                            header("Refresh:0; url=home.php");
                            // prevent further executions
                            exit();
                        }else if(! (bool)$row['is_activated']){
                            $_SESSION['registerEmail'] = $email;
                            echo "<script>alert('You need to activate your account. \nPlease check your email INBOX/SPAM folder for activation code.');</script>";
                            // redirect user to home page
                            header("Refresh:0; url=activate.php");
                            // prevent further executions
                            exit();
                        }else if((bool)$row['is_blocked']){
                            echo "<script>alert('Your account is blocked. Please contact the admin for further help.');</script>";
                            // prevent further executions
                            exit();
                        }                       
                    }else{
                        echo "<script>alert('Invalid sign in credentials.');</script>";
                        header("Refresh:0; url=index.php");
                        // prevent further executions
                        exit();
                    }
                }else{
                    // user account not found
                    echo "<script>alert('Invalid sign in credentials.');</script>";
                    header("Refresh:0; url=index.php");
                    // prevent further executions
                    exit();
                }

                // $hashedPassword = md5($password);
                // $loginQuery = "SELECT id, password, is_normal, is_activated, is_blocked, departmentID as dID  FROM user WHERE email = '$email' AND password = '$hashedPassword'  LIMIT 1";
                // $result = mysqli_query($connection, $loginQuery);
                // if(mysqli_num_rows($result) > 0){
                //     $row = mysqli_fetch_assoc($result);
                //     //   start session
                //     session_start();
                //     if( (bool) $row['is_normal']){
                //         // check if account is activated
                //         if( (bool) $row['is_activated'] && ! (bool) $row['is_blocked'] ){
                //             // store user information to session variable
                //             $_SESSION['loggedIn'] = true;
                //             $_SESSION['userEmail'] = $email;
                //             $_SESSION['userID'] = $row['id'];
                //             $_SESSION['departmentID'] = $row['dID'];
                            
                //             echo "<script>alert('You have successfully signed in.');</script>";
                //             // redirect user to home page
                //             header("Refresh:0; url=home.php");
                //             // prevent further executions
                //             exit();
                //         }else if(! (bool)$row['is_activated']){
                //             $_SESSION['registerEmail'] = $email;
                //             echo "<script>alert('You need to activate your account. \nPlease check your email INBOX/SPAM folder for activation code.');</script>";
                //             // redirect user to home page
                //             header("Refresh:0; url=activate.php");
                //             // prevent further executions
                //             exit();
                //         }else if((bool)$row['is_blocked']){
                //             echo "<script>alert('Your account is blocked. Please contact the admin for further help.');</script>";
                //             // prevent further executions
                //             exit();
                //         }

                //     }else{
                //         echo "<script>alert('Unauthorized access.');</script>";
                //         header("Refresh:0; url=index.php");
                //         // prevent further executions
                //         exit();
                //     }
                // }else{
                //     // user account not found
                //     echo "<script>alert('Invalid sign in credentials.');</script>";
                // }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="assets/css/login.css" />
    <script type="text/javascript" src="assets/js/app.js"></script>
</head>
<body>
    <div class="header">
        <h5 class="logo">CUEA Online Notice Board</h5>
        <div class="nav">
            <a href="register.php">Sign Up</a>
            <a href="contact.php">Contact Us</a>
        </div>
    </div>
    <div class="container">
        <div class="wrapper">
            <h5>Sign In</h5>
            <form method="post"  onsubmit="return checkLoginForm()" >
                <label for="email">Email Address</label><br/>
                <!-- we retain user email address when some error occurs using the value attribute -->
                <input type="email" name="email" id="email" placeholder="someone@mail.com" value='<?php if(isset($_POST['email']) != null) echo $_POST['email']; ?>' />
                <br/>
                <label for="password">Password</label><br/>
                <div class="input-group">
                    <input type="password" placeholder="Password" name="password" id="password" />
                    <span id="span-pwd" onclick="showPassword()">Show</span>
                </div>
                <br/>
                <button type="submit" name="login">Sign In</button>
            </form>
            <div style="margin-top: 10px;">
                <a href="forgot-password.php">Forgot Password ?</a>
            </div>
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
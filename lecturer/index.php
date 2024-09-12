<?php 
    include("../config/config.php");
    include("../config/functions.php");

    if(isset($_POST['login'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(empty($email) || empty($password)){
            echo "<script>alert('All fields are required.');</script>";
        }else{
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo "<script>alert('Please use a valid email address.');</script>";
            }else{
                $sql = "SELECT * FROM user WHERE email = '$email' LIMIT 1";
                $result = mysqli_query($connection, $sql);
                if( mysqli_num_rows($result) > 0 ){
                    $row = mysqli_fetch_assoc($result);
                    if( password_verify($password, $row['password'])){
                        if( (bool) $row['is_lecturer'] && !(bool)$row['is_blocked']){
                            // start session
                            session_start();
                            // store user information to session variable
                            $_SESSION['lecturerLoggedIn'] = true;
                            $_SESSION['lecEmail'] = $email;
                            $_SESSION['lecID'] = $row['id'];
                            $_SESSION['lecLastname'] = $row['lastname'];
                            $_SESSION['lecDepId'] = $row['departmentID'];

                            // redirect user to home page
                            header("Location: dashboard.php");
                            // prevent further executions
                            exit();
                        }else if( (bool) $row['is_blocked']){
                            echo "<script>alert('Account is blocked. Contact the administrator.');</script>";
                        }else{
                            echo "<script>alert('Unathorized access.');</script>";
                        }
                    }else{
                        echo "<script>alert('Invalid user credentials.');</script>";
                    }
                }else{
                    echo "<script>alert('Invalid user credentials.');</script>";
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
    <title>Lecturer Sign In</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/lecturer/login.css" />
    <script type="text/javascript" src="../assets/js/lecturer/app.js"></script>
</head>
<body>
    <div class="container">
        <div class="wrapper">
            <h5>Sign In</h5>
            <form method="post" onsubmit="return checkLoginForm()">
                <label for="email">Email Address</label><br/>
                <input type="email" name="email" id="email" placeholder="someone@mail.com" value="<?php if(isset($email)){ echo $email; } ?>" />
                <br/>
                <label for="password">Password</label><br/>
                <div class="input-group">
                    <input type="password" placeholder="Password" name="password" id="password" />
                    <span id="span-pwd" onclick="showPassword()">Show</span>
                </div>
                <br/>
                <button type="submit" name="login">Sign In</button>
            </form>
            <div style="margin: 10px;">
                <a style="text-decoration: none;font-size: 20px;" href="forgot.php">Forgot Password</a>
            </div>
        </div>
    </div>
</body>
</html>
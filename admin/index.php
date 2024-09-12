<?php 
    include("../config/config.php");
    include("../config/functions.php");

        if(isset($_POST['login'])){
            // using trim() to remove white spaces(blanks) from input 
            $email = cleanInput($_POST['email']);
            $password = cleanInput($_POST['password']);
    
            // check if input values are empty
            if(empty($email) || empty($password)){
                echo "<script>alert('All fields are required.');</script>";
            }else{
                // sanitize email
                $email = sanitizeEmailInput($email);
                // sanitize password
                $password = sanitizeStringInput($password);
    
                // if email address does not have a valid format show message
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    echo "<script>alert('Please use a valid email address.');</script>";
                }else{
                    $loginQuery = "SELECT id, lastname, password, is_admin  FROM user WHERE email = '$email' LIMIT 1";
                    $result = mysqli_query($connection, $loginQuery);
                    if(mysqli_num_rows($result) > 0){
                        $row = mysqli_fetch_assoc($result);
                        if(password_verify($password, $row['password'])){
                            if($row['is_admin']){
                                // start session
                                session_start();
                                // store user information to session variable
                                $_SESSION['adminloggedIn'] = true;
                                $_SESSION['adminEmail'] = $email;
                                $_SESSION['adminID'] = $row['id'];
                                $_SESSION['adminLastname'] = $row['lastname'];

                                // redirect user to home page
                                header("Location: dashboard.php");
                                // prevent further executions
                                exit();
                            }else{
                                echo "<script>alert('Unauthorized access.==>');</script>";
                                header("Refresh:0; url=index.php");
                                // prevent further executions
                                exit();
                            }
                        }else{
                             // user account not found
                             echo "<script>alert('Invalid sign in credentials. ->');</script>";
//                              header("Refresh:0; url=index.php");
                             // prevent further executions
//                              exit();
                        }
                    }else{
                        // user account not found
                        echo "<script>alert('Invalid sign in credentials.-->');</script>";
                        header("Refresh:0; url=index.php");
                        // prevent further executions
                        exit();
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
    <title>Admin Sign In</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/admin/login.css" />
    <script type="text/javascript" src="../assets/js/admin/app.js"></script>
</head>
<body>
    <div class="container">
        <div class="wrapper">
            <h5>Sign In</h5>
            <form method="post" onsubmit="return checkLoginForm()">
                <label for="email">Email Address</label><br/>
                <input type="email" name="email" id="email" placeholder="someone@mail.com"
                        value='<?php if(isset($_POST['email']) != null) echo $_POST['email']; ?>'/>
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
    <?php mysqli_close($connection); ?>
</body>
</html>
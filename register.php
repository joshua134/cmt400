<?php
    include("./config/config.php");
    include("./config/functions.php");

    $passwordPatternRegex = '/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@!&\*\(\)\-+_\=<>]).{6,}$/';
    $specialChars = "@ ! & * - + _  < > ";

    if(isset($_POST['register'])){
        // using trim() to remove white spaces(blanks) from input 
        $firstName = trim($_POST['firstname']);
        $lastName = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $selectedDepartment = $_POST['department'];
        $password = trim($_POST['password']);
        

        // check if input values are empty
        if(empty($email) || empty($firstName) || empty($lastName) || empty($password) || empty($selectedDepartment) ){
            echo "<script>alert('All fields are required.');</script>";
        }else if(!preg_match($passwordPatternRegex, $password)){
            echo "<script>alert('Password must have atleast one uppercase, small case letter, a number, atleast one one of : $specialChars and it cannot be less than 6 characters.');</script>";
        }
        else{
            // sanitize email
            $email = mysqli_real_escape_string($connection,sanitizeEmailInput($email));
            // sanitize password
            $password = mysqli_real_escape_string($connection,sanitizeStringInput($password));
            // sanitize firstname and lastname
            $firstName = mysqli_real_escape_string($connection,sanitizeStringInput($firstName));
            $lastName = mysqli_real_escape_string($connection,sanitizeStringInput($lastName));
            
            // if email address does not have a valid format show message
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo "<script>alert('Please use a valid email address.');</script>";
            }else{
                // check if email already used
                $query = "SELECT *  FROM user WHERE email = '$email' ";
                $result = mysqli_query($connection, $query);
                if(mysqli_num_rows($result) > 0){
                    echo "<script>alert('Email address already used.');</script>";
                }else{
                    // generate activation code
                    $activation_code = rand(100000, 999999);
                    session_start();
                    // check if department number exists
                    if($selectedDepartment != "none" || $selectedDepartment != 'null'){
                        $result = mysqli_query($connection, "SELECT id FROM department WHERE id  = 0".(int)$selectedDepartment);
                        if(mysqli_num_rows($result) < 1){
                            echo "<script>alert('Selected department does not exist.');</script>";
                        }else{
                            // hash password
                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                            $insQuery = "INSERT INTO user(firstname, lastname, email, password, activation_code, departmentID) VALUES ('$firstName','$lastName','$email','$hashedPassword', $activation_code, $selectedDepartment)";
                            if(mysqli_query($connection, $insQuery)){
                                // user account created successfully.
                                echo "<script>alert('User account created successfully.');</script>";
                                $_SESSION['registerEmail'] = $email;
                                // redirect to the activate page
                                header("Refresh: 0; url=activate.php");
                                // prevent further executions
                                exit();
                            }else{
                                echo "Error: ". mysqli_error($connection);
                            }
                        }
                    }else{
                        $hashedPassword = md5($password);
                        $insQuery = "INSERT INTO user(firstname, lastname, email, password, activation_code) VALUES ('$firstName','$lastName','$email','$hashedPassword', $activation_code)";
                        if(mysqli_query($connection, $insQuery)){
                            // user account created successfully.
                            echo "<script>alert('User account created successfully.');</script>";
                            $_SESSION['registerEmail'] = $email;
                            // redirect to the login page
                            header("Refresh:0; url=activate.php");
                            // prevent further executions
                            exit();
                        }else{
                            echo "Error: " . $sql . "<br>" . mysqli_error($connection);
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
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="assets/css/register.css" />
    <script type="text/javascript" src="assets/js/app.js"></script>
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
            <h5>Sign Up</h5>
            <form method="post" onsubmit="return checkRegisterForm()">
                <label for="firstname">First Name</label><br/>
                <input type="text" name="firstname" id="firstname" placeholder="First name" 
                                    value='<?php if(isset($_POST['firstname']) != null) echo $_POST['firstname']; ?>' />
                <br/>
                <label for="lastname">Last Name</label><br/>
                <input type="text" name="lastname" id="lastname" placeholder="Last name"
                                    value='<?php if(isset($_POST['lastname']) != null) echo $_POST['lastname']; ?>'/>
                <br/>
                <label for="email">Email Address</label><br/>
                <input type="email" name="email" id="email" placeholder="someone@mail.com"
                                    value='<?php if(isset($_POST['email']) != null ) echo $_POST['email']; ?>'/>
                <br/>
                <?php 
                    $stmt = $connection->query("SELECT * FROM department");
                    // get all records as associative array
                    $departments = $stmt->fetch_all(MYSQLI_ASSOC);
                ?>
                <label for="department">Department</label>
                <select id="department" name="department">
                    <option value="">Select department...</option>
                    <option value="null">General</option>
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
                <br/>
                <button type="submit" name="register">Sign Up</button>
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
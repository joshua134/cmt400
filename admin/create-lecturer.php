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


    if(isset($_POST['create'])){
        $firstname = htmlspecialchars(sanitizeStringInput(cleanInput($_POST['firstname'])));
        $lastname = htmlspecialchars(sanitizeStringInput(cleanInput($_POST['lastname'])));
        $email = htmlspecialchars(sanitizeEmailInput(cleanInput($_POST['email'])));
        $password = htmlspecialchars(sanitizeStringInput(cleanInput($_POST['password'])));
        $department = cleanInput($_POST['department']);

        if(empty($firstname) || empty($lastname) || empty($email) || empty($password) ){
            echo "<script>alert('All fields are required.');</script>";
        }else{
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo "<script>alert('Please use a valid email address.');</script>";
            }else{
                if(strlen($password) < 6 ){
                    echo "<script>alert('Password cannot be less than 6 characters.');</script>";
                }else{
                    // check user already exists
                    $userExists = mysqli_query($connection, "SELECT * FROM user WHERE email  = '$email' AND is_lecturer = true ");
                    if(mysqli_num_rows($userExists) > 0){
                        echo "<script>alert('Lecturer with this email already registered.');</script>";
                    }else{
                        // create account
                        $now = date("Y-m-d H:m:s");

                        // password hashing.
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                        $sql = "INSERT INTO user(firstname, lastname, email, password, is_lecturer, is_activated, activated_at, departmentID, is_normal)
                                    VALUES 
                                    ('$firstname', '$lastname', '$email', '$hashedPassword', true, true, '$now', $department, false)
                                ";
                        if(mysqli_query($connection, $sql)){
                            echo "<script>alert('Lecturer account created successfully.');</script>";
                        }else{
                            echo " Error ".mysqli_error($connection);
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
    <title>Create Lecturer Account</title>
    <link rel="stylesheet" href="../assets/css/admin/create.css" type="text/css"/>
    <script src="../assets/js/admin/app.js"></script>
</head>
<body>
<div class="header">
        <a href="dashboard.php">Home</a> / <a href="create-lecturer.php">Create Lecturer</a> / <a href="user.php">Users</a> / <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <div class="wrapper">
            <h5>Create Lecturer Account</h5>
            <form method="post"  onsubmit="return lecturerAccountCreate()">
                <label for="firstname">First Name</label><br/>
                <input type="text" placeholder="Firstname" id="firstname" name="firstname" />
                <br/>
                <label for="lastname">Last Name</label><br/>
                <input type="text" placeholder="Lastname " id="lastname" name="lastname" />
                <br/>
                <label for="email">Email Address</label><br/>
                <input type="text" placeholder="someone@m.com" id="email" name="email" />
                <br/>
                <?php 
                    $stmt = $connection->query("SELECT * FROM department");
                    // get all records as associative array
                    $departments = $stmt->fetch_all(MYSQLI_ASSOC);
                ?>
                <label for="department">Lecturer's Department</label><br/>
                <select id="department" name="department">
                    <option value="">Select department...</option>
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
                <input type="password" placeholder="Password" id="password" name="password" />
                <br/>
                <button type="submit" name="create">Create Account</button>
            </form>
            
        </div>
    </div>
    <?php mysqli_close($connection); ?>
</body>
</html>
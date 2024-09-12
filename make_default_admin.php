<?php
    include("./config/config.php");
    echo "<script>alert('Script to run once when application is installed for the first time.');</script>";

    $firstName = "Admin";
    $lastName = "Admin";
    $email = "admin@m.com";
    $password = "Qazwsx@123";
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insQuery = "INSERT INTO user(firstname, lastname, email, password, is_admin, is_normal) VALUES ('$firstName','$lastName','$email','$hashedPassword', true, false)";
    if(mysqli_query($connection, $insQuery)){
        // user account created successfully.
        echo "<script>alert('Admin account created successfully.');</script>";
        header("Refresh: 0; url=admin/");
        // prevent further executions
        exit();
    }else{
        echo "Error: ". mysqli_error($connection);
    }
    mysqli_close($connection);
?>
<?php
    include("./config/config.php");
    include("./config/functions.php");

    if(isset($_POST['contact'])){
        $email = cleanInput($_POST['email']);
        $email = mysqli_real_escape_string($connection, $email);
        $subject = mysqli_real_escape_string($connection, cleanInput($_POST['subject']));
        $message = mysqli_real_escape_string($connection, cleanInput($_POST['message']));

        if(empty($email) || empty($subject) || empty($message) ){
            echo "<script>alert('All fields are required.');</script>";
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Please use a valid email address.');</script>";
        } else {
            $query = "INSERT INTO contact(contact_email, contact_subject, contact_message) VALUES ('$email', '$subject', '$message')";
            if(mysqli_query($connection, $query)){
                // unset the variables to be free
                unset($email, $subject, $message);
                echo "<script>alert('Thank you for contacting us, we will get back to you.');</script>";
            }else{
                echo "Error: ". mysqli_error($connection);
            }
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="assets/css/contact.css"/>
    <script src="assets/js/app.js"></script>

</head>
<body>
    <div class="header">
        <h5 class="logo">CUEA Online Notice Board</h5>
        <div class="nav">
            <a href="index.php">Sign In</a>
            <a href="register.php">Sign Up</a>
        </div>
    </div>
    <div class="container">
        <div class="wrapper">
            <h5>Message Us</h5>
            <form method="post" onsubmit="return checkContactForm()">
                <label for="email">Email Address</label><br/>
                <input type="email" name="email" id="email" value="<?php if(isset($email)){ echo $email; }else{ echo ""; } ?>" placeholder="someone@mail.com"/>
                <br/>
                <label for="subject">Subject</label><br/>
                <input type="text" name="subject" id="subject" placeholder="Subject" value="<?php if(isset($subject)){ echo $subject; }else{ echo ""; } ?>" />
                <br/>
                <label for="message">Message</label><br/>
                <textarea name="message" id="message" placeholder="Type your message here..." rows=5><?php if(isset($message)){ echo $message; }else{ echo ""; } ?></textarea>
                <br/>
                <button type="submit" name="contact">Contact Us</button>
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
</body>
</html>
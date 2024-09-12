<?php 


    $DBHOST = "localhost";
    $DBUSER = "root";
    $DBPASSWORD = "Captain134";
    $DBNAME = "project";


    // host, user, password, database name
    // $connection = new mysqli($DBHOST,$DBUSER, $DBPASSWORD, $DBNAME);
    $connection = mysqli_connect($DBHOST,$DBUSER, $DBPASSWORD, $DBNAME);


    /* check connection */
     //if ($connection->connect_error) {
     //    die("Connection failed: " . $conn->connect_error);
     //    exit();
     //}

    if(!$connection){
	die("Connection failed ". $mysqli_connect_error());
	exit();
	}
?>

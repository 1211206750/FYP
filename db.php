<?php   

$mysqli = new mysqli("localhost", "root", "", "register");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

    $con = mysqli_connect("localhost", "root","", "register") or die(mysql_error());

    $con2 = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());
?>
<?php

$mysqli = new mysqli("localhost", "root", "", "register");
$mysqli2 = new mysqli("localhost", "root", "", "db_shopping_cart");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

return $mysqli;
?>

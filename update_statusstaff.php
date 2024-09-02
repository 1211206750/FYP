<?php
// update_status.php

// Establish a connection to the database
$con = mysqli_connect("localhost", "root", "", "orderlist") or die(mysqli_error());

// Get data from the AJAX request
$productId = mysqli_real_escape_string($con, $_POST['productId']);
$status = mysqli_real_escape_string($con, $_POST['status']);

// Update the status in the database
$query = "UPDATE users SET STATUS = '$status' WHERE id = $productId";
mysqli_query($con, $query);

// Close the database connection
mysqli_close($con);

// Send a response back to the AJAX request
echo 'success';
?>
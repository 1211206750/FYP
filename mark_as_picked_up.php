<?php
session_start();

// Establish a connection to the database
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

// Check if the UID is provided
if (isset($_GET['uid'])) {
    $uid = mysqli_real_escape_string($con, $_GET['uid']);

    // Update the STATUS to 'Picked Up'
    $updateQuery = "UPDATE pickup_detail SET STATUS = 'Picked Up' WHERE UID = '$uid'";
    mysqli_query($con, $updateQuery);

    // You can echo a success message or handle the response as needed
    echo "Order marked as Picked Up successfully";
} else {
    // Handle error if UID is not provided
    echo "Invalid UID";
}

// Close the database connection
mysqli_close($con);
?>

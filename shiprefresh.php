<?php
session_start();

// Establish a connection to the database
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

if (isset($_GET['uid'])) {
    $uid = mysqli_real_escape_string($con, $_GET['uid']);

    // Check if the file_name column is not empty
    $checkQuery = "SELECT file_name FROM shipping_details WHERE UID = '$uid'";
    $checkResult = mysqli_query($con, $checkQuery);

    if ($checkResult) {
        $row = mysqli_fetch_assoc($checkResult);

        // If file_name is not empty, update the STATUS to 'Delivered'
        if (!empty($row['file_name'])) {
            $updateQuery = "UPDATE shipping_details SET STATUS = 'Delivered' WHERE UID = '$uid'";
            $updateResult = mysqli_query($con, $updateQuery);

            if (!$updateResult) {
                echo "Error updating record: " . mysqli_error($con);
            }
        }
    } else {
        echo "Error checking file_name: " . mysqli_error($con);
    }
}

// Close the database connection
mysqli_close($con);
?>

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Return an error message if not logged in
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Check if the required parameters are set
if (!isset($_POST['productId']) || !isset($_POST['newStatus'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit();
}

// Retrieve user inputs
$productId = $_POST['productId'];
$newStatus = $_POST['newStatus'];

// Establish a connection to the database
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

// Update the status in the database
$query = "UPDATE products SET STATUS = '$newStatus' WHERE PID = $productId";
$result = mysqli_query($con, $query);

// Check if the update was successful
if ($result) {
    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status']);
}

// Close the database connection
mysqli_close($con);
?>

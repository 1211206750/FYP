<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the productId is set
    if (isset($_POST['productId'])) {
        $productId = $_POST['productId'];

        // Establish a connection to the database
        $con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

        // Escape and sanitize form data
        $productName = mysqli_real_escape_string($con, $_POST['productName']);
        $productPrice = mysqli_real_escape_string($con, $_POST['productPrice']);
        $productDescription = mysqli_real_escape_string($con, $_POST['productDescription']);

        // Handle image upload
        if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
            $imagePath = '' . basename($_FILES['productImage']['name']);
            move_uploaded_file($_FILES['productImage']['tmp_name'], $imagePath);

            // Update product details with the new image path
            $query = "UPDATE products SET PRODUCT='$productName', PRICE='$productPrice', DESCRIPTION='$productDescription', IMAGE='$imagePath' WHERE PID='$productId'";
        } else {
            // Update product details without changing the image path
            $query = "UPDATE products SET PRODUCT='$productName', PRICE='$productPrice', DESCRIPTION='$productDescription' WHERE PID='$productId'";
        }

        // Execute the query
        if (mysqli_query($con, $query)) {
            // Redirect to product.php after successful update
            header("Location: product.php");
            exit();
        } else {
            echo "Error updating product: " . mysqli_error($con);
        }

        // Close the database connection
        mysqli_close($con);
    }
}
?>

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Establish a connection to the database
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data to prevent SQL injection
    $oid = mysqli_real_escape_string($con, $_POST['oid']);
    $orderNumber = mysqli_real_escape_string($con, $_POST['order_number']);
    $orderDate = mysqli_real_escape_string($con, $_POST['order_date']);
    $uid = mysqli_real_escape_string($con, $_POST['uid']);
    $totalAmt = mysqli_real_escape_string($con, $_POST['total_amt']);

    // Update data in the database
    $updateQuery = "UPDATE orders SET ORDER_NO='$orderNumber', ORDER_DATE='$orderDate', UID='$uid', TOTAL_AMT='$totalAmt' WHERE OID='$oid'";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        // Data updated successfully
        header("Location: complete.php"); // Redirect to the completed order list page
        exit();
    } else {
        // Handle the error, display a message, or redirect as needed
        echo "Error: " . mysqli_error($con); // Add this line for debugging
    }
} else {
    // Retrieve data for the specified OID
    if (isset($_GET['oid'])) {
        $oid = mysqli_real_escape_string($con, $_GET['oid']);
        $query = "SELECT * FROM orders WHERE OID='$oid'";
        $result = mysqli_query($con, $query);
        $orderData = mysqli_fetch_assoc($result);

        if (!$orderData) {
            // Handle error, redirect, or show a message
            echo "Error: Order not found.";
            exit();
        }
    } else {
        // Handle error, redirect, or show a message
        echo "Error: Order ID not provided.";
        exit();
    }
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Completed Order</title>
    <!-- Add your stylesheets and other head elements here -->
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
</head>
<body>

<div class="container">
    <!-- ... Your existing navigation code ... -->

    <section class="main">
        <i><a class="logo">
            <img src="logo.jpeg" alt="">
            <span class="nav-item">Edit Completed Order</span>
        </a></i>

        <div class="main-table">
            <!-- Add your form for editing the data -->
            <?php
            // Check if $orderData is set before using it
            if (isset($orderData)) {
            ?>
                <form method="post" action="complete_edit.php">
                    <!-- Display the existing data for reference -->
                    <label for="order_number">Order Number:</label>
                    <input type="text" name="order_number" id="order_number" value="<?php echo $orderData['ORDER_NO']; ?>" required>

                    <label for="order_date">Order Date:</label>
                    <input type="text" name="order_date" id="order_date" value="<?php echo $orderData['ORDER_DATE']; ?>" required>

                    <label for="uid">User ID:</label>
                    <input type="text" name="uid" id="uid" value="<?php echo $orderData['UID']; ?>" required>

                    <label for="total_amt">Total Amount:</label>
                    <input type="text" name="total_amt" id="total_amt" value="<?php echo $orderData['TOTAL_AMT']; ?>" required>

                    <!-- Add other fields with labels and input elements -->

                    <!-- Add a hidden field to pass OID for updating the correct record -->
                    <input type="hidden" name="oid" value="<?php echo $orderData['OID']; ?>">

                    <button type="submit">Save Changes</button>
                </form>
            <?php
            } else {
                echo "Error: Order data not available.";
            }
            ?>
        </div>
    </section>
</div>

<!-- Add your scripts and other body elements here -->
</body>
</html>

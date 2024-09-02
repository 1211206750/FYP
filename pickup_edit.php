<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Establish a connection to the database
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

// Retrieve data for the specified UID
if (isset($_GET['uid'])) {
    $uid = mysqli_real_escape_string($con, $_GET['uid']); // Use lowercase 'uid'
    $query = "SELECT * FROM pickup_detail WHERE UID='$uid'";
    $result = mysqli_query($con, $query);
    $pickupData = mysqli_fetch_assoc($result);

    if (!$pickupData) {
        // Handle error, redirect, or show a message
    }
} else {
    // Handle error, redirect, or show a message
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data to prevent SQL injection
    $uid = mysqli_real_escape_string($con, $_POST['uid']);
    $firstName = mysqli_real_escape_string($con, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($con, $_POST['last_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $remark = mysqli_real_escape_string($con, $_POST['remark']);
    $pickupDate = mysqli_real_escape_string($con, $_POST['pickup_date']); // Added pickup_date

    // Update data in the database
    $updateQuery = "UPDATE pickup_detail SET FIRST_NAME='$firstName', LAST_NAME='$lastName', EMAIL_ADDRESS='$email', PHONE='$phone', REMARK='$remark', PICKUP_DATE='$pickupDate' WHERE UID='$uid'";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        // Data updated successfully
        header("Location: arvinpickup.php"); // Redirect to the pickup list page
        exit();
    } else {
        // Handle the error, display a message, or redirect as needed
        echo "Error: " . mysqli_error($con); // Add this line for debugging
    }
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Pick Up</title>
    <!-- Add your stylesheets and other head elements here -->
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color: rgb(239, 228, 191); /* Set your desired background color */
        }

        .container {
            background-color: #fff; /* White background */
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-weight: bold;
            text-align: center;
            font-size: 50px; /* or adjust to your preferred size using different units like px, em, etc. */
        }

        button {
            display: block;
            margin: 0 auto;
        }
        /* Your existing styles for form elements go here */
    </style>
</head>
<body>

<div class="container">
    <!-- ... Your existing navigation code ... -->

    <section class="main">
        <i><a class="title">
        <span>Edit Pick Up</span>
        </a></i>

        <div class="main-table">
            <!-- Add your form for editing the data -->
            <form method="post" action="pickup_edit.php">
                <!-- Display the existing data for reference -->
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo $pickupData['FIRST_NAME']; ?>" required><br><br>

                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo $pickupData['LAST_NAME']; ?>" required><br><br>

                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" value="<?php echo $pickupData['EMAIL_ADDRESS']; ?>" required><br><br>

                <label for="phone">Contact Number:</label>
                <input type="text" name="phone" id="phone" value="<?php echo $pickupData['PHONE']; ?>" required><br><br>

                <label for="pickup_date">Pickup Date:</label>
                <input type="date" name="pickup_date" id="pickup_date" value="<?php echo $pickupData['PICKUP_DATE']; ?>" required><br><br>

                <label for="remark">Remark:</label>
                <input type="text" name="remark" id="remark" value="<?php echo $pickupData['REMARK']; ?>" required><br><br>

                <!-- Add a hidden field to pass UID for updating the correct record -->
                <input type="hidden" name="uid" value="<?php echo $pickupData['UID']; ?>"><br>
                

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </section>
</div>

<!-- Add your scripts and other body elements here -->
</body>
</html>

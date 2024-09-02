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
    $userId = mysqli_real_escape_string($con, $_POST['user_id']);
    $firstName = mysqli_real_escape_string($con, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($con, $_POST['last_name']);
    $emailAddress = mysqli_real_escape_string($con, $_POST['email_address']);
    $phoneNumber = mysqli_real_escape_string($con, $_POST['phone_number']);
    // Add other fields as needed

    // Update data in the database
    $updateQuery = "UPDATE users SET FIRST_NAME='$firstName', LAST_NAME='$lastName', EMAIL_ADDRESS='$emailAddress', PHONE='$phoneNumber' WHERE ID='$userId'";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        // Data updated successfully
        header("Location: customer.php"); // Redirect to the customer list page
        exit();
    } else {
        // Handle the error, display a message, or redirect as needed
        echo "Error: " . mysqli_error($con); // Add this line for debugging
    }
} else {
    // Retrieve data for the specified ID
    if (isset($_GET['user_id'])) {
        $userId = mysqli_real_escape_string($con, $_GET['user_id']);
        $query = "SELECT * FROM users WHERE ID='$userId'";
        $result = mysqli_query($con, $query);
        $userData = mysqli_fetch_assoc($result);

        if (!$userData) {
            // Handle error, redirect, or show a message
        }
    } else {
        // Handle error, redirect, or show a message
    }
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
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
            font-size: 45px; /* Adjust the font size as needed */
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        label {
            text-align: left; /* You can adjust this based on your preference */
        }

        button {
            display: block;
            margin: 20px auto 0;
            padding: 10px 20px;
            background-color: #007bff; /* Set your desired button color */
            color: #fff; /* Set your desired text color */
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3; /* Set your desired hover color */
        }

        .main-table {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <section class="main">
    <i><a class="title">
            <span>Edit Customer</span>
        </a></i>

        <div class="main-table">
            <!-- Add your form for editing the data -->
            <form method="post" action="customer_edit.php">
                <!-- Display the existing data for reference -->
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo $userData['FIRST_NAME']; ?>" required><br><br>

                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo $userData['LAST_NAME']; ?>" required><br><br>

                <label for="email_address">Email Address:</label>
                <input type="email" name="email_address" id="email_address" value="<?php echo $userData['EMAIL_ADDRESS']; ?>" required><br><br>

                <label for="phone_number">Phone Number:</label>
                <input type="tel" name="phone_number" id="phone_number" value="<?php echo $userData['PHONE']; ?>" required><br><br>

                <!-- Add other fields with labels and input elements -->

                <!-- Add a hidden field to pass user ID for updating the correct record -->
                <input type="hidden" name="user_id" value="<?php echo $userData['ID']; ?>"><br><br>

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </section>
</div>

<!-- Add your scripts and other body elements here -->
</body>
</html>

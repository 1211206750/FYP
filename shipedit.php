<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Establish a connection to the database
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data to prevent SQL injection
    $uid = mysqli_real_escape_string($con, $_POST['uid']);
    $firstName = mysqli_real_escape_string($con, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($con, $_POST['last_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $postcode = mysqli_real_escape_string($con, $_POST['postcode']);
    $remark = mysqli_real_escape_string($con, $_POST['remark']);
    $image = mysqli_real_escape_string($con, $_FILES['file_name']['name']);
    $pickupdate = mysqli_real_escape_string($con, $_POST['PICKUP_DATE']);
    $time = mysqli_real_escape_string($con, $_POST['time']);

    // Update data in the database
    $updateQuery = "UPDATE shipping_details SET 
    FIRST_NAME='$firstName', 
    LAST_NAME='$lastName', 
    EMAIL_ADDRESS='$email', 
    PHONE='$phone', 
    POSTCODE='$postcode', 
    file_name='$image',
    REMARK='$remark',
    PICKUP_DATE='$pickupdate',
    DELIVERY_TIME='$time'
    WHERE UID='$uid'";

    $updateResult = mysqli_query($con, $updateQuery);
    $targetDirectory = "delivery/uploads/"; // Update this with your desired directory
$targetFile = $targetDirectory . basename($_FILES['file_name']['name']);

// Move the file to the specified directory
if (move_uploaded_file($_FILES['file_name']['tmp_name'], $targetFile)) {
    echo "The file " . basename($_FILES['file_name']['name']) . " has been uploaded.";
} else {
    echo "Sorry, there was an error uploading your file.";
}

    if ($updateResult) {
        // Data updated successfully
        header("Location: ship.php"); // Redirect to the shipment list page
        exit();
    } else {
        // Handle the error, display a message, or redirect as needed
        echo "Error: " . mysqli_error($con); // Add this line for debugging
    }
} if (isset($_GET['uid'])) {
    $uid = mysqli_real_escape_string($con, $_GET['uid']);
    $query = "SELECT * FROM shipping_details WHERE UID='$uid'";
    $result = mysqli_query($con, $query);
    $shipmentData = mysqli_fetch_assoc($result);

    // Check if shipmentData is valid
    if (!$shipmentData) {
        // Handle error, redirect, or show a message
    } else {
        // Initialize $time here
        $time = $shipmentData['DELIVERY_TIME'];
    }
} else {
    // Handle error, redirect, or show a message
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Shipment</title>
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
    <!-- ... Your existing navigation code ... -->

    <section class="main">
        <i><a class="title">
            <span>Edit Delivery</span>
        </a></i>

        <div class="main-table">
            <!-- Add your form for editing the data -->
            <form method="post" action="shipedit.php" style="width: 20%;" enctype="multipart/form-data">
                <!-- Display the existing data for reference -->
                <div style="display: flex; flex-direction: column; gap: 10px;">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo $shipmentData['FIRST_NAME']; ?>" required><br>

                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo $shipmentData['LAST_NAME']; ?>" required><br>

                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" value="<?php echo $shipmentData['EMAIL_ADDRESS']; ?>" required><br>

                <label for="phone">Contact Number:</label>
                <input type="text" name="phone" id="phone" value="<?php echo $shipmentData['PHONE']; ?>" required><br>

                <label for="postcode">Postcode:</label>
                <input type="text" name="postcode" id="postcode" value="<?php echo $shipmentData['POSTCODE']; ?>" required><br>

                <label for="file_name">New Image:</label></br>
                <input type="file" id="file_name" name="file_name" accept="delivery/uploads/*" /></br></br>

                <label for="pickup_date">Delivery Date:</label>
                <input type="date" name="PICKUP_DATE" id="pickup_date" value="<?php echo $shipmentData['PICKUP_DATE']; ?>" required><br><br>    

                <label for="time">Delivery Time:</label>
                <select name="time" id="time" required>
                <option value="10:00-15:00" <?php echo ($time == '10:00-15:00') ? 'selected' : ''; ?>>10:00-15:00</option>
                <option value="14:00-18:00" <?php echo ($time == '14:00-18:00') ? 'selected' : ''; ?>>14:00-18:00</option>
                </select><br><br>


                <label for="remark">Remark:</label>
                <input type="text" name="remark" id="remark" value="<?php echo $shipmentData['REMARK']; ?>" required><br>

                <!-- Add a hidden field to pass UID for updating the correct record -->
                <input type="hidden" name="uid" value="<?php echo $shipmentData['UID']; ?>"><br>
                </div>

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </section>
</div>

<!-- Add your scripts and other body elements here -->
</body>
</html>

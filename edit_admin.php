<?php
session_start();

include("db.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Initialize a flag to indicate successful update
$update_success = false;

// Fetch the user data based on user ID
$query = "SELECT * FROM form WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    // Redirect to login page if user data not found
    header("location: login.php");
    exit();
}

// Handle form submission for updating user data
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $new_username = mysqli_real_escape_string($con, $_POST['new_username']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $new_tel_number = mysqli_real_escape_string($con, $_POST['new_tel_number']);
    $new_email = mysqli_real_escape_string($con, $_POST['new_email']);

    // Update the user data in the database
    $update_query = "UPDATE form SET name='$new_username', password_plain='$new_password', tel='$new_tel_number', email='$new_email' WHERE id='$user_id'";
    $update_result = mysqli_query($con, $update_query);

    if ($update_result) {
        // Set the flag to true on successful update
        $update_success = true;
    } else {
        echo "<script type='text/javascript'> alert('Failed to update user data')</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User Data</title>
    <link rel="stylesheet" href="registerstyle.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0; /* Optional background color for the entire page */
        }

        .edit-user {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center text within .edit-user */
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form label {
            margin-bottom: 10px; /* Add margin below each label for spacing */
        }

        form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px; /* Add margin below each input for spacing */
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: auto; /* Adjust width for the submit button */
        }
    </style>
</head>
<body>
    <div class="edit-user"> 
        <h1>Edit Admin Details</h1>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="new_username" value="<?php echo $user_data['name']; ?>" required>
            <label>Password:</label>
            <input type="password" name="new_password" value="<?php echo $user_data['password_plain']; ?>" required>
            <label>Tel Number:</label>
            <input type="text" name="new_tel_number" value="<?php echo $user_data['tel']; ?>" required>
            <label>Email:</label>
            <input type="email" name="new_email" value="<?php echo $user_data['email']; ?>" required>
            <input type="submit" value="Update">
        </form>
        <p><a href="login.php">Back to Login Page</a></p>

        <script>
            // Display alert on successful update
            <?php
            if ($update_success) {
                echo "alert('User data updated successfully');";
            }
            ?>
        </script>
    </div>
</body>
</html>

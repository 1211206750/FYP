<?php
session_start();

include("db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['name'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM form WHERE name = '$username' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if ($user_data['password_plain'] == $password) {
                // Update session variables
                $_SESSION['username'] = $user_data['name'];
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['is_admin'] = $user_data['is_admin'];

                // Redirect to adminpage.php after successful login
                header("location: adminpage.php");
                exit(); // Make sure to exit after redirection
            }
        }
        echo "<script type='text/javascript'> alert('Wrong Username or Password')</script>";
    } else {
        echo "<script type='text/javascript'> alert('Please enter both username and password')</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Login and Register</title>
    <link rel="stylesheet" href="registerstyle.css">
</head>
<body>
    <div class="login"> 
        <h1>Login</h1>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="name" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <input type="submit" name="" value="Login">
        </form>
        <p>Not have an account? <a href="register.php">Sign Up Here</a></p>
        <p><a href="forgot-password.php">Forget Password?</a></p>
    </body>
</html>

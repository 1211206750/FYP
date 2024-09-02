<?php
    session_start();

    include("db.php");

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $password = $_POST['password_plain'];

        if(!empty($email) && !empty($password) && !is_numeric($email))
        {
            $query = "INSERT INTO form (name, email, tel, password_plain) VALUES ('$name', '$email', '$tel', '$password')";

            mysqli_query($con, $query);

            echo "<script type='text/javascript'> alert('Successfully Register')</script>";

        }
        else
        {
            echo "<script type='text/javascript'> alert('Please Enter Some Valid Information')</script>";
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
    <div class="register">
        <h1>Register</h1>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="name" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Phone Number</label>
            <input type="tel" name="tel" required>
            <label>Password</label>
            <input type="password" name="password_plain" required>
            <input type="submit" name="" value="submit">
        </form>
        <p>Already Have and account?<a href="login.php">Login Here</a></p>
    </div>
</body>
</html>
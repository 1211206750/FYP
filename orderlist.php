<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delivery Page</title>
    <style>
        #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #customers td, #customers th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
        }
    </style>
</head>
<body>
    <link rel="stylesheet" href="hfer.css">
    <header>
        <h1>Welcome back, staff </h1>
    </header>
    <nav>
        <a href="profile.php">Profile</a>
        <a href="orderlist.php">Order List</a>
        <a href="upload.php">Order Image</a>
        <a href="help.php">Help</a>
    </nav>
    <footer>
    <p>If have any issue in page please visit our contact at Help page. Kindly please do not share your profile.</p>
    </footer>
    
    <h2>Please Keep Safety Ride...</h2>
    <table id="customers">
        <thead>
            <tr>
                <th>Delivery ID</th>
                <th>Order ID</th>
                <th>Date and Time</th>
                <th>Customer First Name</th>
                <th>Customer Last Name</th>
                <th>Customer Address</th>
                <th>Customer Phone.No</th>
                <th>Customer Email</th>
                <th>Pick Up Image</th>
                <th>Complete Image</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Database connection
                $hostname = "localhost";
                $username = "root";
                $password = "";
                $database = "orderlist";

                $con = mysqli_connect($hostname, $username, $password, $database);

                if (!$con) {
                    die("Database Connection Failed: " . mysqli_connect_error());
                }

                // Fetch data from the database
                $sql = "SELECT DID, OID, date, first_name, last_name, cus_address, cus_hp, cus_email, pick_image, done_image FROM cus_details";
                $result = mysqli_query($con, $sql);

                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['DID']}</td>
                                <td>{$row['OID']}</td>
                                <td>{$row['date']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['cus_address']}</td>
                                <td>{$row['cus_hp']}</td>
                                <td>{$row['cus_email']}</td>
                                <td>{$row['pick_image']}</td>
                                <td>{$row['done_image']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No orders found</td></tr>";
                }

                // Close the database connection
                mysqli_close($con);
            ?>
        </tbody>
    </table>
</body>
</html>

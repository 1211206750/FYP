<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Check if product ID is set in the query string
if (!isset($_GET['uid'])) {
    // Redirect to product.php if product ID is not provided
    header("Location: product.php");
    exit();
}

// Retrieve admin name from session
$adminName = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Establish a connection to the database
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

// Fetch product data based on the provided PID
$productId = mysqli_real_escape_string($con, $_GET['uid']);
$query = "SELECT * FROM products WHERE PID = '$productId'";
$result = mysqli_query($con, $query);

// Check if the product exists
if (mysqli_num_rows($result) == 0) {
    // Redirect to product.php if the product is not found
    header("Location: product.php");
    exit();
}

// Fetch product details
$productData = mysqli_fetch_assoc($result);

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Edit Product</title>
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
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-weight: bold;
            text-align: center;
            font-size: 50px; /* Adjust the font size as needed */
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        label {
            text-align: center; /* You can adjust this based on your preference */
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
            max-width: 20%;
            height: auto;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="title">Edit Product</div>
        <form action="update_product.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="productId" value="<?php echo $productData['PID']; ?>" />

            <!-- Display the previous image -->
            <label>Previous Image:</label>
            <img src="images/<?php echo $productData['IMAGE']; ?>" alt="Previous Image" /><br /><br />

            <!-- Allow the user to upload a new image -->
            <label for="productImage">New Image:</label>
            <input type="file" id="productImage" name="productImage" accept="image/*" /><br /><br />

            <label for="productName">Product Name:</label>
            <input type="text" id="productName" name="productName" value="<?php echo $productData['PRODUCT']; ?>" required /><br /><br />

            <label for="productPrice">Product Price:</label>
            <input type="text" id="productPrice" name="productPrice" value="<?php echo $productData['PRICE']; ?>" required /><br /><br />

            <label for="productDescription">Product Description:</label>
            <textarea id="productDescription" name="productDescription"
                required><?php echo $productData['DESCRIPTION']; ?></textarea><br /><br />

            <!-- Add more fields as needed for other product attributes -->

            <button type="submit">Save Changes</button>
        </form>
    </div>

    <!-- Include any additional scripts as needed -->

</body>

</html>

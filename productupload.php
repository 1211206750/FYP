<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission and database insertion here
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productDescription = $_POST['product_description'];

    // File upload handling

    $originalFileName = basename($_FILES["product_image"]["name"]);
    $extension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
    $uniqueFileName = uniqid() . "_" . time() . "." . $extension;
    $targetFile = $uniqueFileName;


    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["product_image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
    $destinationPath = "OnlineFlorist/images/"; // Update this path to the correct one
    $destinationFile = $destinationPath . $targetFile;

    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $destinationFile)) {
            // File uploaded successfully, now insert data into the database
            // Establish a connection to the database
            $con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

            // Escape user inputs for security
            $productName = mysqli_real_escape_string($con, $productName);
            $productPrice = mysqli_real_escape_string($con, $productPrice);
            $productDescription = mysqli_real_escape_string($con, $productDescription);
            $productImage = mysqli_real_escape_string($con, $targetFile);

            // Insert data into the "products" table
            $query = "INSERT INTO products (PRODUCT, PRICE, DESCRIPTION, IMAGE) VALUES ('$productName', '$productPrice', '$productDescription', '$productImage')";
            $result = mysqli_query($con, $query);

            if ($result) {
                // Product added successfully
                header("Location: product.php"); // Redirect to product list page
                exit();
            } else {
                // Error adding product
                echo "Error: " . mysqli_error($con);
            }

            // Close the database connection
            mysqli_close($con);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
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

        h2 {
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

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: auto;
            padding: 10px 20px;
            background-color: #007bff; /* Set your desired button color */
            color: #fff; /* Set your desired text color */
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Set your desired hover color */
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Add New Product</h2>
        <form action="productupload.php" method="post" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" required><br><br>

            <label for="product_price">Product Price:</label>
            <input type="text" name="product_price" required><br><br>

            <label for="product_description">Product Description:</label>
            <textarea name="product_description" required></textarea><br><br>

            <label for="product_image">New Image:</label><br>
            <input type="file" id="product_image" name="product_image" accept="images/*" required><br><br>

            <input type="submit" value="Upload Product">
        </form>
    </div>

</body>

</html>
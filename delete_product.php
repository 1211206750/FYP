<?php
// delete_product.php

$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

if (isset($_POST['productID'])) {
    $productID = $_POST['productID'];
    $query = "DELETE FROM products WHERE PID = $productID";
    $result = mysqli_query($con, $query);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Product deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting product"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Product ID not provided"]);
}

mysqli_close($con);
?>
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve admin name from session
$adminName = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Add any other logic you need for product.php

?>
<span style="font-family: verdana, geneva, sans-serif;"><!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <title>Product List</title>
      <link rel="stylesheet" href="style.css" />
      <!-- Font Awesome Cdn Link -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
      <style>
        #uploadProductBtn {
            float: right;
            margin-right: 20px;
        }

        body {
            background: rgb(239, 228, 191);
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        nav {
            position: relative;
            top: 0;
            bottom: 0;
            height: 1000px;
            left: 0;
            background: rgb(235, 211, 145);
            width: 500px;
            overflow: hidden;
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1);
            flex: 0 0 330px; /* Set the initial state to visible */
        }

        th, td {
            border: 2px solid #000; /* Add this line to make the border bold */
            padding: 8px;
            text-align: left;
            }

        /* Add the new styles */
        #toggleButton {
            position: fixed;
            top: 20px;
            left: 20px;
            cursor: pointer;
            z-index: 999;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    </head>
    <body>

      <div class="container">
      <nav>
          <ul>
            <i><a class="logo2">
              <img src="logo.jpeg" alt="">  
            </a></i>
            <div class="adminname">
              <h1><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?></h1>
            </div>

            <i><a href="adminpage.php">
              <i class="fas fa-home"></i>
              <span class="nav-item">Home</span>
            </a></i>
            <i><a href="product.php">
              <i class="fas fa-boxes"></i>
              <span class="nav-item">Product List</span>
            </a></i>
            <i><a href="complete.php">
              <i class="fas fa-clipboard-list"></i>
              <span class="nav-item">Order List</span>
            </a></i>
            <i><a href="arvinpickup.php">
              <i class="fas fa-handshake"></i>
              <span class="nav-item">Pick Up List</span>
            </a></i>
            <i><a href="ship.php">
              <i class="fas fa-truck"></i>
              <span class="nav-item">Delivery List</span>
            </a></i>
            <i><a href="deliverystaff.php">
              <i class="fas fa-people-carry"></i>
              <span class="nav-item">Delivery Staff List</span>
            </a></i>
            <i><a href="customer.php">
              <i class="fas fa-address-book"></i>
              <span class="nav-item">Customer List</span>
            </a></i>
            <i><a href="contact.php">
              <i class="fas fa-phone"></i>
              <span class="nav-item">Contact Message</span>
            </a></i>
            <i><a href="edit_admin.php">
              <i class="fas fa-people-arrows"></i>
              <span class="nav-item">Edit Profile</span>
            </a></i>
            <i><a href="login.php" class="logout">
              <i class="fas fa-sign-out-alt"></i>
              <span class="nav-item">Log out</span>
            </a></i>
          </ul>
        </nav>
        <div id="toggleButton" onclick="toggleContainerVisibility()">
        <i class="fas fa-bars"></i>  Menu
        </div>

        

        <section class="main">
          <i><a class="logo">
            <img src="logo.jpeg" alt="">
            <span class="nav-item">Product List</span>
          </a></i>
          <button id="uploadProductBtn">Upload New Product</button><br>
          <div class="main-table">
            <table>
              <thead>
                <tr>
                  <th>Product ID</th>
                  <th>Product Name</th>
                  <th>Product Price</th>
                  <th>Image</th>
                  <th style="text-align: center;">Description</th>
                  <th>Status</th> 
                  <th>In Stock</th> 
                  <th>Out Of Stock</th> 
                  <th>Edit</th> 
                </tr>
              </thead>
              
              <tbody>
            <?php
              // Establish a connection to the database
              $con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

              // Fetch data from the "products" table
              $query = "SELECT * FROM products ORDER BY PID DESC";
              $result = mysqli_query($con, $query);

              // Display data in the table
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['PID']}</td>";
                echo "<td>{$row['PRODUCT']}</td>";
                echo "<td>{$row['PRICE']}</td>";
                $imagePath = 'OnlineFlorist/images/' . $row['IMAGE'];
                echo "<td style='text-align: center;'><img src='{$imagePath}' alt='{$row['PRODUCT']}' style='width: 100px; height: 100px;'></td>";
                echo "<td>{$row['DESCRIPTION']}</td>";
                echo "<td>{$row['STATUS']}</td>";
                echo "<td><button class='statusButton' data-productid='{$row['PID']}' data-status='In Stock'>In Stock</button></td>";
                echo "<td><button class='statusButton' data-productid='{$row['PID']}' data-status='Out Of Stock'>Out Of Stock</button></td>";
                echo "<td><button onclick=\"location.href='product_edit.php?uid={$row['PID']}'\">Edit</button></td>";
                echo "</tr>";
              }

              // Close the database connection
              mysqli_close($con);
            ?>
          </tbody>
            </table>  
    </form> 
          </div>
       </section>
      </div>

    

<script>
$(document).ready(function () {
    $('.statusButton').on('click', function () {
        const productId = $(this).data('productid');
        const newStatus = $(this).data('status');

        // Send an AJAX request to update the status
        $.ajax({
            type: 'POST',
            url: 'update_status.php',
            data: { productId: productId, newStatus: newStatus },
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                // You can update the UI or perform any other actions
                // For simplicity, you can reload the page for demonstration purposes
                location.reload();
            },
            error: function () {
                alert('Error updating status');
            }
        });
    });
});

</script>
<script>
var originalFlex = '0 0 330px';
function toggleContainerVisibility() {
var nav = document.querySelector('.container nav');
var currentFlex = window.getComputedStyle(nav).getPropertyValue('flex');

// Toggle between the original width and 0px
nav.style.flex = (currentFlex === '0 0 0px' || currentFlex === '0px 0px 0px') ? originalFlex : '0 0 0px';
}
</script>

<script>
    document.getElementById("uploadProductBtn").addEventListener("click", function() {
        window.location.href = "productupload.php";
    });
</script>
    </body>
    </html></span>
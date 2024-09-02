<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error($con));

$pickupOrderQuery = "SELECT COUNT(*) as total_pickup FROM pickup_detail";
$pickupOrderResult = mysqli_query($con, $pickupOrderQuery);

if (!$pickupOrderResult) {
    die('Error: ' . mysqli_error($con));
}

$pickupOrderData = mysqli_fetch_assoc($pickupOrderResult);

$shipOrderQuery = "SELECT COUNT(*) as total_ship FROM shipping_details";
$shipOrderResult = mysqli_query($con, $shipOrderQuery);

if (!$shipOrderResult) {
    die('Error: ' . mysqli_error($con));
}

$shipOrderData = mysqli_fetch_assoc($shipOrderResult);

// Connect to the 'orderlist' database for the 'users' table
$conOrderList = mysqli_connect("localhost", "root", "", "orderlist") or die(mysqli_error($conOrderList));

$deliveryStaffQuery = "SELECT COUNT(*) as total_staff FROM users";
$deliveryStaffResult = mysqli_query($conOrderList, $deliveryStaffQuery);

if (!$deliveryStaffResult) {
    die('Error: ' . mysqli_error($conOrderList));
}

$deliveryStaffData = mysqli_fetch_assoc($deliveryStaffResult);

$completeOrdersQuery = "SELECT COUNT(*) as total_orders FROM orders";
$completeOrdersResult = mysqli_query($con, $completeOrdersQuery);

if (!$completeOrdersResult) {
    die('Error: ' . mysqli_error($con));
}

$completeOrdersData = mysqli_fetch_assoc($completeOrdersResult);

?>


<span style="font-family: verdana, geneva, sans-serif;"><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
  <style>
        /* Your existing styles */

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

        /* Add the new styles */
        #toggleButton {
            position: fixed;
            top: 20px;
            left: 20px;
            cursor: pointer;
            z-index: 999;
        }
    </style>
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
        <section class="main">
          <i><a class="logo">
            <img src="logo.jpeg" alt="">
            <span class="nav-item">DashBoard</span>
          </a></i>

          <div class="main-skills">
            <div class="card">
              <i class="fas fa-handshake"></i>
              <h3>Total PickUp Order</h3>
              <button><?php echo $pickupOrderData['total_pickup']; ?></button>

            </div>
            <div class="card">
              <i class="fas fa-truck"></i>
              <h3>Total Ship Order</h3>
              <button><?php echo $shipOrderData['total_ship']; ?></button>
            </div>
            <div class="card">
              <i class="fas fa-people-carry"></i>
              <h3>Total Delivery Staff</h3>
              <button><?php echo $deliveryStaffData['total_staff']; ?></button>
            </div>
            <div class="card">
              <i class="fas fa-clipboard-list"></i>
              <h3>Total Order</h3>
              <button><?php echo $completeOrdersData['total_orders']; ?></button>
            </div>
          </div>
          <div id="toggleButton" onclick="toggleContainerVisibility()">
        <i class="fas fa-bars"></i>  Menu
    </div>

          <canvas id="barChart" width="400" height="200"></canvas>
          <script>
  // Get the context of the canvas element
  var ctx = document.getElementById('barChart').getContext('2d');

  // Data for the bar chart
  var data = {
    labels: ['Total PickUp Order', 'Total Ship Order', 'Total Delivery Staff', 'Completed Order'],
    datasets: [{
      label: 'Orders',
      data: [
        <?php echo $pickupOrderData['total_pickup']; ?>,
        <?php echo $shipOrderData['total_ship']; ?>,
        <?php echo $deliveryStaffData['total_staff']; ?>,
        <?php echo $completeOrdersData['total_orders']; ?>,
        1, // Total Delivery Staff (replace with your actual data)
        1  // Completed Order (replace with your actual data)
      ],
      backgroundColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
      ],
      borderWidth: 1
    }]
  };

  // Configuration options
  var options = {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  };

  // Create the bar chart
  var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: data,
    options: options
  });
</script>
          
        </section>
      </div>
      <script>

        var originalFlex = '0 0 330px';
        function toggleContainerVisibility() {
        var nav = document.querySelector('.container nav');
        var currentFlex = window.getComputedStyle(nav).getPropertyValue('flex');
        
        // Toggle between the original width and 0px
        nav.style.flex = (currentFlex === '0 0 0px' || currentFlex === '0px 0px 0px') ? originalFlex : '0 0 0px';
        }
    </script>
    </body>
    </html></span>
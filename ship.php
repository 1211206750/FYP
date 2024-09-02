<?php
session_start();

// Establish a connection to the database
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

// Check if the user is logged in
if (isset($_GET['delivery_date'])) {
    $filteredDate = mysqli_real_escape_string($con, $_GET['delivery_date']);
    $query = "SELECT shipping_details.*, orders.ORDER_NO FROM shipping_details
              LEFT JOIN orders ON shipping_details.UID = orders.UID
              WHERE DELIVERY_TIME = '$filteredDate'";
} else {
    $query = "SELECT shipping_details.*, orders.ORDER_NO FROM shipping_details
              LEFT JOIN orders ON shipping_details.UID = orders.UID";
}

$result = mysqli_query($con, $query);

// Retrieve admin name from session
$adminName = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// ... Your existing code ...
?>
<span style="font-family: verdana, geneva, sans-serif;"><!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <title>Delivery List</title>
      <link rel="stylesheet" href="style.css" />
      <!-- Font Awesome Cdn Link -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
      <style>
        /* Your existing styles */

        body {
            background: rgb(239, 228, 191);
            min-height: 100vh;
        }

        th, td {
            border: 2px solid #000; /* Add this line to make the border bold */
            padding: 8px;
            text-align: left;
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

        #closeButton{
          margin-top: 50px;
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
            <span class="nav-item">Delivery List</span>
          </a></i>

          <div class="filter-form">
        <form method="get" action="ship.php">
        <label for="delivery_date">Filter by Delivery Date:</label>
        <input type="date" id="delivery_date" name="delivery_date">
        <button type="button" onclick="showFilteredData()">Filter</button>
        </form>

        <form method="get" action="ship.php" onsubmit="return showSearchResult()">
        <label for="ORDER_NO">Search:</label>
        <input type="text" id="ORDER_NO" name="ORDER_NO">
        <button type="submit">Search</button>
        </form>

        <div id="popupContent" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); z-index: 1000;">


    <button id="closeButton">Close</button>
</div>


          <div class="main-table">
            <table>
              <thead>
                <tr>
                <th>Invoice Number</font></th>
                <th>Delivery ID</font></th>
                <th>Product Name</font></th>
                <th>Customer First Name</font></th>
                <th>Customer Last Name</font></th>
                <th>Email Address</font></th>
                <th>Contact Number</font></th>
                <th>Postcode</font></th>
                <th>Remark</font></th>
                <th>Delivery Date</font></th>
                <th>Delivery Time</font></th>
                <th>Proof</font></th>
                <th>Status</font></th>
                <th>Edit</font></th>
                </tr>
              </thead>
              <tbody>
              <div id="toggleButton" onclick="toggleContainerVisibility()">
              <i class="fas fa-bars"></i>  Menu
              </div>
              <?php
              // Establish a connection to the database
              $con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());


              $query = "SELECT shipping_details.*, order_details.PNAME, orders.ORDER_NO 
          FROM shipping_details
          LEFT JOIN orders ON shipping_details.UID = orders.UID
          LEFT JOIN order_details ON orders.OID = order_details.OID";

$result = mysqli_query($con, $query);

// Display data in the table
while ($rowAll = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$rowAll['ORDER_NO']}</td>";
    echo "<td>{$rowAll['ID']}</font></td>";

    // Check if 'PNAME' is present in the row
    if (isset($rowAll['PNAME'])) {
        echo "<td>{$rowAll['PNAME']}</td>";
    } else {
        echo "<td>No Product Name</td>"; // You can adjust this message accordingly
    }
                echo "<td>{$rowAll['FIRST_NAME']}</font></td>";
                echo "<td>{$rowAll['LAST_NAME']}</font></td>";
                echo "<td>{$rowAll['EMAIL_ADDRESS']}</font></td>";
                echo "<td>{$rowAll['PHONE']}</font></td>";
                echo "<td>{$rowAll['POSTCODE']}</font></td>"; 
                echo "<td>{$rowAll['REMARK']}</font></td>";
                echo "<td>{$rowAll['PICKUP_DATE']}</font></td>"; 
                echo "<td>{$rowAll['DELIVERY_TIME']}</font></td>"; 
                $imagePath = 'delivery/uploads/' . $rowAll['file_name'];
                echo "<td style='text-align: center;'><img src='{$imagePath}'style='width: 100px; height: 100px;'></td>";
                echo "<td>{$rowAll['STATUS']}</font><button onclick=\"refreshStatus('{$rowAll['UID']}')\">Refresh</button></td>";
                echo "<td><button onclick=\"location.href='shipedit.php?uid={$rowAll['UID']}'\">Edit</button></td>";
                echo "</tr>";
              }

              // Close the database connection
              mysqli_close($con);
            ?>
              </tbody>
            </table>
          </div>
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

<script>
    // Set the event listener for the close button after the popup is displayed
    function showFilteredData() {
        var popupContent = document.getElementById('popupContent');

        // Redirect to the same page with the filter parameter
        fetch('shipfetchdata.php?delivery_date=' + document.getElementById('delivery_date').value)
            .then(response => response.text())
            .then(data => {
                popupContent.innerHTML = data;
                
                // Append the close button and set its event listener
                var closeButton = document.createElement('button');
                closeButton.textContent = 'Close';
                closeButton.id = 'closeButton';
                closeButton.addEventListener('click', function () {
                    closePopup();
                });

                popupContent.appendChild(closeButton);

                // Show the pop-up
                popupContent.style.display = 'block';
            });
    }

    function showSearchResult() {
        var searchOrderNo = document.getElementById('ORDER_NO').value;
        var popupContent = document.getElementById('popupContent');

        // Fetch and display the search results in the existing popup
        fetch('ship_fetch_search_result.php?ORDER_NO=' + searchOrderNo)
            .then(response => response.text())
            .then(data => {
                // Update the content of the popup
                popupContent.innerHTML = data;

                var closeButton = document.createElement('button');
                closeButton.textContent = 'Close';
                closeButton.id = 'closeButton';
                closeButton.addEventListener('click', function () {
                    closePopup();
                });

                popupContent.appendChild(closeButton);

                // Show the popup
                popupContent.style.display = 'block';
            });

        // Prevent the form from submitting and reloading the page
        return false;
    }

    function closePopup() {
        var popupContent = document.getElementById('popupContent');
        popupContent.style.display = 'none';
    }
</script>

<script>
    function refreshStatus(uid) {
        // Use AJAX to call shiprefresh.php with the UID parameter
        fetch('shiprefresh.php?uid=' + uid)
            .then(response => response.text())
            .then(data => {
                // Refresh the page or handle the response as needed
                location.reload();
            })
            .catch(error => console.error('Error:', error));
    }
</script>


    </body>
    
    </html></span>
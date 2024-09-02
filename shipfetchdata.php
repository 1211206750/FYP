<?php
// fetchdata.php

// Establish a connection to the database
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

// Get the filtered date from the query parameters
$filteredDate = mysqli_real_escape_string($con, $_GET['delivery_date']);

// Fetch data from the "PICKUP" table based on the filtered date
$query = "SELECT shipping_details.*, orders.ORDER_NO, order_details.PNAME
FROM shipping_details
LEFT JOIN orders ON shipping_details.UID = orders.UID
LEFT JOIN order_details ON orders.OID = order_details.OID
WHERE shipping_details.PICKUP_DATE = '$filteredDate';
";

$result = mysqli_query($con, $query);

// Check if there are any rows
if (mysqli_num_rows($result) > 0) {
    // Display data in the pop-up
    echo '<table>';
    echo '<thead>
        <tr>
        <th>Invoice Number</th>
        <th>Customer First Name</th>
        <th>Customer Last Name</th>
        <th>Email Address</th>
        <th>Phone no</th
        ><th>Postcode</th>
        <th>Remark</th>
        <th>Delivery Date</th>
        <th>Delievery Time</th>
        <th>Status</th>
        <th>Proof</th>
        <th>Edit</th>
        </tr>
        </thead>';
    echo '<tbody>';
    while ($rowAll = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$rowAll['ORDER_NO']}</font></td>";
        echo "<td>{$rowAll['FIRST_NAME']}</font></td>";
        echo "<td>{$rowAll['LAST_NAME']}</font></td>";
        echo "<td>{$rowAll['EMAIL_ADDRESS']}</font></td>";
        echo "<td>{$rowAll['PHONE']}</font></td>";
        echo "<td>{$rowAll['POSTCODE']}</font></td>"; 
        echo "<td>{$rowAll['REMARK']}</font></td>";
        echo "<td>{$rowAll['PICKUP_DATE']}</font></td>"; 
        echo "<td>{$rowAll['DELIVERY_TIME']}</font></td>"; 
        echo "<td>{$rowAll['STATUS']}</font><button onclick=\"refreshStatus('{$rowAll['UID']}')\">Refresh</button></td>"; 
        $imagePath = 'delivery/uploads/' . $rowAll['file_name'];
        echo "<td style='text-align: center;'><img src='{$imagePath}'style='width: 100px; height: 100px;'></td>";
        echo "<td><button onclick=\"location.href='shipedit.php?uid={$rowAll['UID']}'\">Edit</button></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    // Display a message when there are no pickups for the selected date
    echo "There is no Shipping for the selected date.";
}

// Close the database connection
mysqli_close($con);
?>

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

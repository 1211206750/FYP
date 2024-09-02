<?php
// Add database connection code
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

$searchOrderNo = isset($_GET['ORDER_NO']) ? mysqli_real_escape_string($con, $_GET['ORDER_NO']) : '';
$query = "SELECT shipping_details.*, orders.ORDER_NO, order_details.PNAME 
FROM shipping_details
LEFT JOIN orders ON shipping_details.UID = orders.UID
LEFT JOIN order_details ON orders.OID = order_details.OID
WHERE orders.ORDER_NO LIKE '%$searchOrderNo%';
";
$result = mysqli_query($con, $query);

// Check if there are search results
if (mysqli_num_rows($result) > 0) {
    // Display search results in a table
    echo '<table>';
    echo '<thead>
        <tr>
        <th>Invoice Number</th>
        <th>Customer First Name</th>
        <th>Customer Last Name</th>
        <th>Email Address</th>
        <th>Phone no</th>
        <th>Postcode</th>
        <th>Remark</th>
        <th>SDelivery Date</th>
        <th>Delievery Time</th>
        <th>Proof</th>
        <th>Status</th>
        <th>Edit Status</th>
        <th>Edit</th>
        </tr>
        </thead>';
    echo '<tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo "<td>{$row['ORDER_NO']}</font></td>";
        echo "<td>{$row['ID']}</font></td>";
        echo "<td>{$row['FIRST_NAME']}</font></td>";
        echo "<td>{$row['LAST_NAME']}</font></td>";
        echo "<td>{$row['EMAIL_ADDRESS']}</font></td>";
        echo "<td>{$row['PHONE']}</font></td>";
        echo "<td>{$row['POSTCODE']}</font></td>"; 
        echo "<td>{$row['REMARK']}</font></td>";
        echo "<td>{$row['PICKUP_DATE']}</font></td>"; 
        echo "<td>{$row['DELIVERY_TIME']}</font></td>"; 
        $imagePath = 'delivery/uploads/' . $row['file_name'];
        echo "<td style='text-align: center;'><img src='{$imagePath}'style='width: 100px; height: 100px;'></td>";
        echo "<td>{$row['STATUS']}</font><button onclick=\"refreshStatus('{$row['UID']}')\">Refresh</button></td>";
        echo "<td><button onclick=\"location.href='shipedit.php?uid={$row['UID']}'\">Edit</button></td>";
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo 'No matching results found.';
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

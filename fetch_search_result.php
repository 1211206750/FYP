<?php
// Add database connection code
$con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());

$searchOrderNo = isset($_GET['ORDER_NO']) ? mysqli_real_escape_string($con, $_GET['ORDER_NO']) : '';
$query = "SELECT pickup_detail.*, orders.ORDER_NO FROM pickup_detail
          LEFT JOIN orders ON pickup_detail.UID = orders.UID
          WHERE ORDER_NO LIKE '%$searchOrderNo%'";
$result = mysqli_query($con, $query);

// Check if there are search results
if (mysqli_num_rows($result) > 0) {
    // Display search results in a table
    echo '<table>';
    echo '<thead><tr><th>Invoice Number</th><th>Customer First Name</th><th>Customer Last Name</th><th>Email Address</th><th>Phone no</th><th>Remark</th><th>Pickup Date</th><th>Status</th><th>Edit Status</th><th>Edit</th></tr></thead>';
    echo '<tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['ORDER_NO'] . '</td>';
        echo '<td>' . $row['FIRST_NAME'] . '</td>';
        echo '<td>' . $row['LAST_NAME'] . '</td>';
        echo '<td>' . $row['EMAIL_ADDRESS'] . '</td>';
        echo '<td>' . $row['PHONE'] . '</td>';
        echo '<td>' . $row['REMARK'] . '</td>';
        echo '<td>' . $row['PICKUP_DATE'] . '</td>';
        echo '<td>' . $row['STATUS'] . '</td>';
        echo "<td><button onclick=\"markAsPickedUp('{$row['UID']}')\">Picked Up</button></td>";
        echo "<td><button onclick=\"location.href='pickup_edit.php?uid={$row['UID']}'\">Edit</button></td>";
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
    function markAsPickedUp(uid) {
        // Use AJAX to update the database without refreshing the page
        fetch('mark_as_picked_up.php?uid=' + uid)
            .then(response => response.text())
            .then(data => {
                // You can handle the response if needed
                console.log(data);

                // Optionally, you can reload the page or update the UI accordingly
                location.reload();
            });
    }
</script>

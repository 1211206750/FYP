<?php

// Include database connection
$mysqli = require __DIR__ . "/database.php";

// Check if $mysqli is a valid connection
if (!$mysqli || is_int($mysqli)) {
    die("Database connection error: " . mysqli_connect_error());
}

$email = $_POST["email"];

// Function to generate a unique token
function generateUniqueToken($mysqli) {
    do {
        $token = bin2hex(random_bytes(16) . uniqid(mt_rand(), true));
    } while (checkIfTokenExists($mysqli, $token));

    return $token;
}

// Function to check if a token exists
function checkIfTokenExists($mysqli, $token) {
    $sql = "SELECT COUNT(*) FROM form WHERE reset_token_hash = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}

// Generate a unique token
$token = generateUniqueToken($mysqli);

// Hash the token
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

// Update the database with the new token
$sql = "UPDATE form
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);

// Check if the prepare statement was successful
if (!$stmt) {
    die("Prepare statement failed: " . $mysqli->error);
}

$stmt->bind_param("sss", $token_hash, $expiry, $email);

// Check if the execution of the statement was successful
if (!$stmt->execute()) {
    $errorMessage = $stmt->error;
    $sqlQuery = $stmt->debugDumpParams(); // Log the SQL query

    if ($stmt->errno == 1062) {
        // Duplicate entry error
        error_log("Duplicate entry error: Token collision. Duplicate token: $token");
        error_log("SQL Query: $sqlQuery");
        die("Duplicate entry error: Token collision. Please check the error log for details.");
    } else {
        die("Execution failed: $errorMessage");
    }
}

echo "Password reset information updated successfully.";

// Include mailer configuration
$mail = require __DIR__ . "/mailer.php";

$mail->setFrom("noreply@gmail.com");
$mail->addAddress($email); // Assuming $email is the recipient's email address
$mail->Subject = "Password Reset";
$mail->Body = <<<END
    Click <a href="http://localhost:4433/fyp/reset-password.php?token=$token">here</a> to reset your password.
END;

try {
    // Send the email
    $mail->send();
    echo "Message sent, please check your inbox.";
} catch (Exception $e) {

}


// Close prepared statement and database connection
$stmt->close();
$mysqli->close();
?>

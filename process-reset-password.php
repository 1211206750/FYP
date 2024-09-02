<?php
$token = $_POST["token"];
$password = $_POST["password"];
$password_confirmation = $_POST["password_confirmation"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sql = "SELECT * FROM form
            WHERE reset_token_hash = ?";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param("s", $token_hash);

    $stmt->execute();

    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    if ($user === null) {
        $message = "Token not found";
    } elseif (strtotime($user["reset_token_expires_at"]) <= time()) {
        $message = "Token has expired";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters";
    } elseif (!preg_match("/[a-z]/i", $password)) {
        $message = "Password must contain at least one letter";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $message = "Password must contain at least one number";
    } elseif ($password !== $password_confirmation) {
        $message = "Passwords must match";
    } else {
        // Update password without using password hash (not recommended)
        $sql = "UPDATE form
                SET password_plain = ?,
                    reset_token_hash = NULL,
                    reset_token_expires_at = NULL
                WHERE id = ?";

        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param("si", $password, $user["id"]);

        $stmt->execute();

        $message = "Password updated. You can now login.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <!-- Add your stylesheets and other head content here -->
</head>
<body>
    <div>
        <p><?php echo isset($message) ? $message : ''; ?></p>
    </div>

    <div class="edit-user">
        <form method="post" action="">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <label for="password">New Password</label>
            <input type="password" id="password" name="password">

            <label for="password_confirmation">Repeat Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation">

            <button type="submit">Send</button>
        </form>
    </div>

    <!-- Add your additional HTML content or scripts here -->
</body>
</html>

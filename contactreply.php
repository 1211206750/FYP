<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve admin name from session
$adminName = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Include PHPMailer library and mailer configuration
$mail = require __DIR__ . "/mailer.php";

// Include database connection
$mysqli = require __DIR__ . "/database.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $contactId = $_POST['contact_id'];
    $userEmail = $_POST['user_email'];
    $replyContent = $_POST['replyContent'];

    // Perform any additional processing or validation if needed

    try {
        // Set email parameters
        $mail->setFrom("noreply@gmail.com", "Micson");
        $mail->addAddress($userEmail); // Assuming $userEmail is the recipient's email address
        $mail->Subject = 'Reply to Your Contact Message';
        $mail->isHTML(true);
        $mail->Body = $replyContent;

        // Send email
        $mail->send();
        echo 'Email sent successfully!';

        // Perform any additional actions after sending the email

        // Update the database with the reply information
        $stmt = $mysqli->prepare("UPDATE contact_messages SET reply_content = ? WHERE id = ?");
        $stmt->bind_param("si", $replyContent, $contactId);
        $stmt->execute();
        $stmt->close();

    } catch (Exception $e) {
        echo "Error: {$e->getMessage()}";
        error_log("Email sending error: " . $e->getMessage());
    }

    // Redirect back to the contact page or any other page
    header("Location: contact.php");
    exit();
} else {
    // Display the reply form
    $contactId = $_GET['contact_id'] ?? '';
    $userEmail = $_GET['user_email'] ?? '';
}

?>
<!-- Display the form for replying -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include necessary styles and scripts -->
    <title>Reply to Contact Message</title>
</head>
<body>

    <h2>Reply to Contact Message</h2>
    <form action="contactreply.php" method="post">
        <input type="hidden" name="contact_id" value="<?php echo htmlspecialchars($contactId); ?>" />
        <input type="hidden" name="user_email" value="<?php echo htmlspecialchars($userEmail); ?>" />
        <label for="replyContent">Your Reply:</label>
        <textarea name="replyContent" id="replyContent" rows="4" required></textarea>
        <button type="submit">Send Reply</button>
        <button type="button" onclick="window.history.back()">Cancel</button>
    </form>

</body>
</html>

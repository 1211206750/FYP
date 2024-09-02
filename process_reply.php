<?php
// process_reply.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contactId = $_POST['contact_id'];
    $userEmail = $_POST['user_email'];
    $replyContent = $_POST['replyContent'];

    // Send email using PHPMailer (make sure to include PHPMailer in your project)
    require 'vendor/autoload.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    // Configure PHPMailer settings (SMTP, etc.)
    // ...

    // Set recipient email
    $mail->addAddress($userEmail);

    // Set email content
    $mail->Subject = 'Reply to your contact message';
    $mail->Body = $replyContent;

    // Send the email
    if ($mail->send()) {
        // Update the database to mark the message as replied
        $con = mysqli_connect("localhost", "root", "", "db_shopping_cart") or die(mysqli_error());
        $updateQuery = "UPDATE tblcontact SET replied = 1 WHERE contact_id = $contactId";
        mysqli_query($con, $updateQuery);
        mysqli_close($con);

        echo 'Reply sent successfully.';
    } else {
        echo 'Error sending reply: ' . $mail->ErrorInfo;
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid request method.';
}
?>

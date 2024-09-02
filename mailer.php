<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer(true);

$mail->SMTPDebug = 0; // Set to SMTP::DEBUG_SERVER, SMTP::DEBUG_CLIENT, or SMTP::DEBUG_OFF
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or PHPMailer::ENCRYPTION_SMTPS
$mail->Port = 587; // or 465
$mail->Username = "micsonflorist@gmail.com";
$mail->Password = "kahe rdua vbbh xdpl";

$mail->isHTML(true);

// Set the sender's name and address
$mail->setFrom("micsonflorist@gmail.com", "Micson");


return $mail;

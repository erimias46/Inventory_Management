<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

function setupMailer()
{
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'inventory.yurostock.com';       // Set the SMTP server to send through
        $mail->SMTPAuth = true;                  // Enable SMTP authentication
        $mail->Username = 'inventory@yurostock.com'; // SMTP username
        $mail->Password = 'eN!$n*7jjjJF';       // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587;                       // TCP port to connect to

        // Email details
        $mail->setFrom('inventory@yurostock.com', 'Mailer');
        return $mail;
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return null;
    }
}





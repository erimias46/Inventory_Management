<?php

include 'config.php';
include 'db.php'; // Assuming you have a file for database connection

function sendEmailToSubscribers($message, $subject, $con)
{
    // Fork the process to run the email sending asynchronously
    $pid = pcntl_fork();

    if ($pid == -1) {
        // Fork failed
        die('Could not fork');
    } elseif ($pid) {
        // Parent process: return immediately
        return;
    } else {
        // Child process: handle the email sending

        // Get the configured mailer
        $mail = setupMailer();
        if ($mail === null) {
            exit("Mailer setup failed.");
        }

        // Get all emails from the email_subscribers table
        $query = "SELECT email FROM email_subscribers";
        $result = $con->query($query); // Assuming $con is your database connection object

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $email = $row['email'];

                try {
                    // Set the recipient
                    $mail->addAddress($email);

                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body    = "<h1>Message</h1><p>" . nl2br($message) . "</p>";

                    // Send email
                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent to $email. Mailer Error: {$mail->ErrorInfo}\n";
                }

                // Clear all recipients for the next loop
                $mail->clearAddresses();
            }
        } else {
            echo "No subscribers found.";
        }

        // Exit the child process after finishing the task
        exit(0);
    }
}

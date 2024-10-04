<?php



include 'config.php';
include 'db.php'; // Assuming you have a file for database connection

function sendEmailToSubscribers($message, $con)
{
    // Get the configured mailer
    $mail = setupMailer();
    if ($mail === null) {
        exit("Mailer setup failed.");
    }

    // Get all emails from the email_subscribers table
    $query = "SELECT email FROM email_subscribers";
    $result = $con->query($query); // Assuming $con is your database connection object

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()
        ) {
            $email = $row['email'];

            try {
                // Set the recipient
                $mail->addAddress($email);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'New Product Added';
                $mail->Body    = "<h1>New Jeans Added</h1><p>" . nl2br($message) . "</p>";

                // Send email
                $mail->send();
                //echo "Message has been sent to $email\n";
            } catch (Exception $e) {
                echo "Message could not be sent to $email. Mailer Error: {$mail->ErrorInfo}\n";
            }

            // Clear all recipients for the next loop
            $mail->clearAddresses();
        }
    } else {
        echo "No subscribers found.";
    }

    $con->close(); // Close the database connection
}

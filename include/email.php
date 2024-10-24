<?php
require 'config.php';
require 'db.php';

class BatchEmailSender
{
    private $mailer;
    private $db;
    private $batchSize = 50; // Adjust based on your server capabilities
    private $maxProcesses = 5; // Adjust based on your server capabilities
    private $processes = [];

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function setupMailerInstance()
    {
        $mailer = setupMailer(); // Your existing mailer setup function

        // Additional performance optimizations for PHPMailer
        $mailer->SMTPKeepAlive = true; // Keep SMTP connection alive between sends
        $mailer->Timeout = 10; // Reduce timeout for faster failure detection

        return $mailer;
    }

    private function getSubscribers()
    {
        $stmt = $this->db->prepare("SELECT email FROM email_subscribers");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function sendBatch($emails, $subject, $message)
    {
        $mailer = $this->setupMailerInstance();

        if (!$mailer) {
            throw new Exception("Failed to setup mailer");
        }

        // Pre-compile email content
        $mailer->isHTML(true);
        $mailer->Subject = $subject;
        $mailer->Body = "<h1>Message</h1><p>" . nl2br($message) . "</p>";

        // Use BCC for batch sending
        $mailer->clearAddresses();
        foreach ($emails as $email) {
            $mailer->addBCC($email['email']);
        }

        try {
            $mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Batch sending failed: " . $e->getMessage());
            return false;
        } finally {
            $mailer->clearAddresses();
            $mailer->clearBCCs();
        }
    }

    public function sendEmailToSubscribers($subject, $message)
    {
        $subscribers = $this->getSubscribers();
        $totalSubscribers = count($subscribers);

        if ($totalSubscribers === 0) {
            return "No subscribers found.";
        }

        // Split subscribers into batches
        $batches = array_chunk($subscribers, $this->batchSize);
        $totalBatches = count($batches);

        // Process monitoring
        $succeeded = 0;
        $failed = 0;

        foreach ($batches as $index => $batch) {
            // Fork a new process for each batch
            $pid = pcntl_fork();

            if ($pid == -1) {
                // Fork failed
                die('Could not fork process');
            } else if ($pid) {
                // Parent process
                $this->processes[] = $pid;

                // Limit concurrent processes
                if (count($this->processes) >= $this->maxProcesses) {
                    $this->waitForChild();
                }
            } else {
                // Child process
                try {
                    $result = $this->sendBatch($batch, $subject, $message);
                    exit($result ? 0 : 1);
                } catch (Exception $e) {
                    error_log("Process failed: " . $e->getMessage());
                    exit(1);
                }
            }
        }

        // Wait for remaining processes
        while (count($this->processes) > 0) {
            $this->waitForChild();
        }

        return [
            'total' => $totalSubscribers,
            'succeeded' => $succeeded,
            'failed' => $failed
        ];
    }

    private function waitForChild()
    {
        $pid = pcntl_wait($status);
        if ($pid > 0) {
            $key = array_search($pid, $this->processes);
            if ($key !== false) {
                unset($this->processes[$key]);
            }
        }
    }
}

// Usage example
function sendEmailToSubscribers($message, $subject, $con)
{
    $sender = new BatchEmailSender($con);
    return $sender->sendEmailToSubscribers($subject, $message);
}

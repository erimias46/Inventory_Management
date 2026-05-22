<?php

// Webhook endpoint only (not for browser visits).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(200);
    exit('OK');
}

require __DIR__ . '/vendor/autoload.php';

use Telegram\Bot\Api;

$telegram = new Api('7938846333:AAEmBwXas0iuu2tMF3HJ2jhXzf_egE1sJT8');

include __DIR__ . '/include/db.php';

try {
    $update = $telegram->getWebhookUpdates();
} catch (Throwable $e) {
    error_log('telegram_bot: ' . $e->getMessage());
    http_response_code(200);
    exit;
}

$message = $update->getMessage();
if ($message) {
    $chat_id = $message->getChat()->getId();
    $first_name = $message->getChat()->getFirstName();

    if ($message->getText() === '/start') {
        // Insert or update subscriber in the database
        $sql = "INSERT INTO subscribers (chat_id, first_name) VALUES ('$chat_id', '$first_name') 
                ON DUPLICATE KEY UPDATE first_name='$first_name'";
        mysqli_query($con, $sql);

        // Send a welcome message back to the user
        $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => "Welcome $first_name! You have subscribed to notifications."
        ]);
    }
}

http_response_code(200);

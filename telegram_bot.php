<?php

// Include the Telegram Bot SDK
require 'vendor/autoload.php'; // Ensure you have installed the Telegram SDK

use Telegram\Bot\Api;

// Create a new instance of the Telegram API
$telegram = new Api('7938846333:AAEmBwXas0iuu2tMF3HJ2jhXzf_egE1sJT8'); // Replace with your Bot Token

// Database connection
include('include/db.php'); // Update this

// Get webhook updates from Telegram
$update = $telegram->getWebhookUpdates();

// Check if the update contains a message
if ($update->getMessage()) {
    $chat_id = $update->getMessage()->getChat()->getId(); // Get user's chat_id
    $first_name = $update->getMessage()->getChat()->getFirstName(); // Get user's first name

    // Check if this is the /start command
    if ($update->getMessage()->getText() === '/start') {
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

<?php
require 'vendor/autoload.php'; // Autoload Composer dependencies
use Telegram\Bot\Api;

$telegram = new Api('7048538445:AAFH9g9L2EHfmH8mHK7N8CPt82INxhdzev0');

// Handle Telegram updates
$update = $telegram->getWebhookUpdates();
$chatId = $update->getMessage()->getChat()->getId();
$text = $update->getMessage()->getText();

$pdo = new PDO('mysql:host=localhost;dbname=inventory', 'root', '');

// Handle subscriptions
if ($text === '/subscribe') {
    $stmt = $pdo->prepare("INSERT INTO subscribers (chat_id) VALUES (:chat_id) ON DUPLICATE KEY UPDATE subscribed_at = NOW()");
    $stmt->bindParam(':chat_id', $chatId);
    $stmt->execute();
    $telegram->sendMessage([
        'chat_id' => $chatId,
        'text' => "You have successfully subscribed to sale notifications."
    ]);
} elseif ($text === '/unsubscribe') {
    $stmt = $pdo->prepare("DELETE FROM subscribers WHERE chat_id = :chat_id");
    $stmt->bindParam(':chat_id', $chatId);
    $stmt->execute();
    $telegram->sendMessage([
        'chat_id' => $chatId,
        'text' => "You have successfully unsubscribed from sale notifications."
    ]);
}

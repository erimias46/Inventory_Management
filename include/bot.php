<?php


function sendMessageToSubscribers($message, $con)
{
    // Fetch all subscribers
    $subscribers_query = "SELECT chat_id FROM subscribers";
    $subscribers_result = mysqli_query($con, $subscribers_query);
    $subscribers = mysqli_fetch_all($subscribers_result, MYSQLI_ASSOC);

    // Bot Token
    $botToken = "7535344872:AAFoyqpzsXU_c-USN5HOuEV6bV9IYQERb0o"; // Replace with your bot token
    $apiUrl = "https://api.telegram.org/bot$botToken/sendMessage";

    // Loop through each subscriber and send the message
    foreach ($subscribers as $subscriber) {
        $chatId = $subscriber['chat_id'];

        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML' // Optional: Use HTML formatting
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}


?>
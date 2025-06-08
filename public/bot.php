<?php
declare(strict_types=1);

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$token = $_ENV['TELEGRAM_BOT_TOKEN'] ?? null;
if (!$token) {
    http_response_code(500);
    echo 'Bot token not configured';
    exit;
}

$update = json_decode(file_get_contents('php://input'), true);
if (!$update) {
    exit;
}

if (!isset($update['message']['chat']['id'], $update['message']['text'])) {
    exit;
}

$chatId = $update['message']['chat']['id'];
$text   = $update['message']['text'];

$responseText = 'You said: ' . $text;

$sendMessageUrl = sprintf('https://api.telegram.org/bot%s/sendMessage', $token);

$data = [
    'chat_id' => $chatId,
    'text'    => $responseText,
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'timeout' => 5,
    ],
];

$context = stream_context_create($options);
file_get_contents($sendMessageUrl, false, $context);

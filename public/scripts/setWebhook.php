<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$token = $_ENV['TELEGRAM_BOT_TOKEN'] ?? null;
$domain = $_ENV['TELEGRAM_WEBHOOK_DOMAIN'] ?? null;

if (!$token || !$domain) {
    fwrite(STDERR, "TELEGRAM_BOT_TOKEN or TELEGRAM_WEBHOOK_DOMAIN is missing in .env\n");
    exit(1);
}

$webhookUrl = rtrim($domain, '/') . '/bot.php';

$ch = curl_init(sprintf('https://api.telegram.org/bot%s/setWebhook', $token));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, ['url' => $webhookUrl]);

$response = curl_exec($ch);
if ($response === false) {
    fwrite(STDERR, 'Curl error: ' . curl_error($ch) . PHP_EOL);
    exit(1);
}

echo $response . PHP_EOL;
curl_close($ch);
<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$sid = env('TWILIO_SID');
$token = env('TWILIO_AUTH_TOKEN');
$from = env('TWILIO_WHATSAPP_FROM');
$to = 'whatsapp:+529994506364';

echo "Testing Twilio...\n";
echo "SID: $sid\n";
echo "From: $from\n";

try {
    $client = new \Twilio\Rest\Client($sid, $token);
    $message = $client->messages->create($to, [
        'from' => $from,
        'body' => 'Test message from Healthify'
    ]);
    echo "Message SID: " . $message->sid . "\n";
    echo "Status: " . $message->status . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

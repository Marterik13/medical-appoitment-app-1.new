<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = env('CALLMEBOT_API_KEY');
$phone = '5219994506364'; // El número que me pasaste
$text = urlencode("Prueba de Healthify - Manual Test");

echo "Testing CallMeBot...\n";
echo "Phone: $phone\n";
echo "API Key: $apiKey\n";

$url = "https://api.callmebot.com/whatsapp.php?phone=$phone&text=$text&apikey=$apiKey";

$response = file_get_contents($url);

echo "Response: $response\n";

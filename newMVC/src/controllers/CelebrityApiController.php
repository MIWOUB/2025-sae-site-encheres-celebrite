<?php
header('Content-Type: application/json');

$name = $_GET['name'];
if (!$name) {
    echo json_encode([]);
    exit;
}

$apiKey = getenv('API_NINJAS_KEY') ?: '';
if ($apiKey === '') {
    http_response_code(500);
    echo json_encode(['error' => 'API_NINJAS_KEY missing']);
    exit;
}

$url = "https://api.api-ninjas.com/v1/celebrity?name=" . urlencode($name);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Api-Key: $apiKey"]);
$response = curl_exec($ch);
curl_close($ch);

echo $response;

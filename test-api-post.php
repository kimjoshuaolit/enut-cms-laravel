<?php

echo "Testing POST request to enutV2 API...\n\n";

$url = 'http://localhost:8000/api/upload-image';

// Create a fake form data
$data = [
    'category' => 'Test Category'
];

// Try to POST without file first
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status: $httpCode\n";

if ($error) {
    echo "❌ CURL Error: $error\n";
    echo "\nThis means enutV2 server is not reachable!\n";
    echo "Make sure enutV2 is running on port 8000\n";
} else {
    echo "✅ Connection successful!\n";
    echo "Response preview:\n";
    echo substr($response, 0, 500) . "\n";
}

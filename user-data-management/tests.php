<?php

// Path to the CSV file
$csvFile = realpath(__DIR__ . '/user-data-management/testFile.csv');

// API URL for uploading data
$url = 'http://localhost:8000/api/upload';

// Initialize cURL
$curl = curl_init();

// Set cURL options
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, [
    'file' => new CURLFile($csvFile)
]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Enable verbose output for debugging
curl_setopt($curl, CURLOPT_VERBOSE, true);
$verbose = fopen('php://temp', 'w+');
curl_setopt($curl, CURLOPT_STDERR, $verbose);

// Execute cURL request
$response = curl_exec($curl);

// Check for errors
if (curl_errno($curl)) {
    $error_msg = curl_error($curl);
    echo "cURL Error: $error_msg\n";
} else {
    echo "Response: $response\n";
}

// Output verbose information
rewind($verbose);
$verbose_log = stream_get_contents($verbose);
echo "Verbose Information:\n$verbose_log\n";

// Close cURL
curl_close($curl);

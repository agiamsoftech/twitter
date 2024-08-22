<?php
// Initialize cURL session
$ch = curl_init();

// Set the URL for the request
curl_setopt($ch, CURLOPT_URL, "https://api.twitter.com/2/tweets");

// Set the HTTP method to POST
curl_setopt($ch, CURLOPT_POST, true);

// Set the headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: OAuth oauth_consumer_key="iLlfBFDqlXIpMbTfIArF4b7Ar",oauth_token="409020007-J3pSZrvzvCjKkokZD6fPU9N4tZUWeMMSXMFvNxHj",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1724322286",oauth_nonce="zZd1VSFqiFm",oauth_version="1.0",oauth_signature="3i3TgAgDW8CjCWLwBOAI%2FBBex70%3D"'
]);

// Set the request data
$data = [
    "text" => "with image",
    "media" => [
        "media_ids" => [
            "1826565931352698880"
        ]
    ]
];
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Return the response as a string instead of outputting it directly
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request and get the response
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // Output the response
    echo $response;
}

// Close the cURL session
curl_close($ch);
?>

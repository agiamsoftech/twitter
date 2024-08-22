<?php

$bearer_token = 'AAAAAAAAAAAAAAAAAAAAAIvlvQEAAAAAnlFzDI%2BEOHteF%2Bhd7sdw3z1Z4WQ%3DgjuRdKFB8rJb0juJCxe4UitvD9dIl82wapAND4BxP3EpXdCtIz';  // You must generate a Bearer token for v2

// curl --request GET 'https://api.x.com/2/tweets/409020007?tweet.fields=public_metrics' --header 'Authorization: Bearer <Bearer Token>'
// $url = 'https://api.twitter.com/2/tweets';
// https://api.x.com/2/users/by/username/agsonuali
$tweet = "Hello Twitter! #MyFirstTweet";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.x.com/2/tweets/409020007?tweet.fields=public_metrics");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["text" => $tweet]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $bearer_token",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

print_r(json_decode($response));
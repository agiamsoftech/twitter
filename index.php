<?php
$consumerKey = 'iLlfBFDqlXIpMbTfIArF4b7Ar';
$consumerSecret = 'qY68PSBUjuNP79QDEPa6kMHVcJvdgfZ48BwLkEI4feTawtltK4';
$accessToken = '409020007-J3pSZrvzvCjKkokZD6fPU9N4tZUWeMMSXMFvNxHj';
$accessTokenSecret = 'bDOVRo5Woqq8hRtAl1jfFeZPqvVnqdw3TNtzCDl1Qo8XX';

$imagePath = 'https://t4.ftcdn.net/jpg/02/45/03/61/360_F_245036112_Lf5C4B2zfWbVGoF1rHAj7IFdLFiSXDQj.jpg';
$status = 'This is a test tweet with an image!';

$mediaId = uploadImageToTwitter($imagePath, $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

if ($mediaId) {
    $response = postTweetWithImage($status, $mediaId, $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
    print_r($response);
} else {
    echo "Failed to upload image.";
}

function uploadImageToTwitter($imagePath, $apiKey, $apiSecretKey, $accessToken, $accessTokenSecret) {
    $url = "https://upload.twitter.com/1.1/media/upload.json";
    
    $oauth = array(
        'oauth_consumer_key' => $apiKey,
        'oauth_token' => $accessToken,
        'oauth_nonce' => time(),
        'oauth_timestamp' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_version' => '1.0'
    );

    $base_info = buildBaseString($url, 'POST', $oauth);
    $composite_key = rawurlencode($apiSecretKey) . '&' . rawurlencode($accessTokenSecret);
    $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
    $oauth['oauth_signature'] = $oauth_signature;

    $header = array(buildAuthorizationHeader($oauth), 'Content-Type: multipart/form-data');

    $postfields = array(
        'media' => new CURLFile(realpath($imagePath))
    );

    $options = array(
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    curl_close($ch);

    $media = json_decode($response);
    print_r($media);exit;
    return $media->media_id_string ?? null;
}

function postTweetWithImage($status, $mediaId, $apiKey, $apiSecretKey, $accessToken, $accessTokenSecret) {
    $url = "https://api.twitter.com/2/tweets";
    
    $oauth = array(
        'oauth_consumer_key' => $apiKey,
        'oauth_token' => $accessToken,
        'oauth_nonce' => time(),
        'oauth_timestamp' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_version' => '1.0'
    );

    $postfields = json_encode(array(
        'text' => $status,
        'media' => array(
            'media_ids' => array($mediaId)
        )
    ));

    $base_info = buildBaseString($url, 'POST', $oauth);
    $composite_key = rawurlencode($apiSecretKey) . '&' . rawurlencode($accessTokenSecret);
    $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
    $oauth['oauth_signature'] = $oauth_signature;

    $header = array(
        buildAuthorizationHeader($oauth), 
        'Content-Type: application/json'
    );

    $options = array(
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
}

function buildBaseString($baseURI, $method, $params) {
    $r = array();
    ksort($params);
    foreach($params as $key => $value) {
        $r[] = "$key=" . rawurlencode($value);
    }
    return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}

function buildAuthorizationHeader($oauth) {
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key => $value)
        $values[] = "$key=\"" . rawurlencode($value) . "\"";
    $r .= implode(', ', $values);
    return $r;
}

?>
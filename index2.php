<?php

// this is proper work
$consumerKey = 'iLlfBFDqlXIpMbTfIArF4b7Ar';
$consumerSecret = 'qY68PSBUjuNP79QDEPa6kMHVcJvdgfZ48BwLkEI4feTawtltK4';
$accessToken = '409020007-J3pSZrvzvCjKkokZD6fPU9N4tZUWeMMSXMFvNxHj';
$accessTokenSecret = 'bDOVRo5Woqq8hRtAl1jfFeZPqvVnqdw3TNtzCDl1Qo8XX';

function uploadImageToTwitter($imagePath, $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret) {
    
    $url = "https://upload.twitter.com/1.1/media/upload.json";    
    
    // Initialize cURL
    $ch = curl_init();

    // Set the URL and other necessary options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: multipart/form-data',
        'Authorization: OAuth oauth_consumer_key="'.$consumerKey.'", oauth_token="'.$accessToken.'", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1724320433", oauth_nonce="nH4GDos8gXp", oauth_version="1.0", oauth_signature="VrBhdCZJ0w%2F2tmUqw3bo6KUQSd8%3D"'
    ]);
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'media' => new CURLFile($imagePath)
    ]);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        echo "cURL Error: $error_msg";
    } else {
        $media = json_decode($response, true);
        return $media['media_id_string'] ?? null;
    }
    curl_close($ch);

}

function postTweetWithImage($status, $mediaId, $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret) {
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.twitter.com/2/tweets");
    
    curl_setopt($ch, CURLOPT_POST, true);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: OAuth oauth_consumer_key="'.$consumerKey.'",oauth_token="'.$accessToken.'",oauth_signature_method="HMAC-SHA1",oauth_timestamp="1724322286",oauth_nonce="zZd1VSFqiFm",oauth_version="1.0",oauth_signature="3i3TgAgDW8CjCWLwBOAI%2FBBex70%3D"'
    ]);
    
    $data = [
        "text" => $status,
        "media" => [
            "media_ids" => [
                $mediaId
            ]
        ]
    ];
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        $array = json_decode($response, true);
        $twitter_post_id = $array['data']['id'] ?? null;
        return $twitter_post_id;
    }

    curl_close($ch);
}


if(isset($_POST['Submit'])){
    $imagePath = $_FILES['media_image']['tmp_name'];
    $status = isset($_POST['message']) ? $_POST['message'] : null;
    $mediaId = uploadImageToTwitter($imagePath, $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);    
    
    if ($mediaId) {
        
        $response = postTweetWithImage($status, $mediaId, $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
        print_r($response);
        
    } else {
        echo "Failed to upload image.";
    }

}


?>

<form action="" method="post" enctype="multipart/form-data">
  <label for="message">Caption</label><br>
  <input type="text" id="message" name="message" value="John"><br>
  <label>Choose Image<span class="text-danger">  </span></label><br>
  <input name="media_image" type="file" ><br>
  <input type="submit" name="Submit" value="Submit">
</form>

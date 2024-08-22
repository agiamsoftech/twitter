<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

// Twitter API credentials
$consumerKey = 'iLlfBFDqlXIpMbTfIArF4b7Ar';
$consumerSecret = 'qY68PSBUjuNP79QDEPa6kMHVcJvdgfZ48BwLkEI4feTawtltK4';
$accessToken = '409020007-J3pSZrvzvCjKkokZD6fPU9N4tZUWeMMSXMFvNxHj';
$accessTokenSecret = 'bDOVRo5Woqq8hRtAl1jfFeZPqvVnqdw3TNtzCDl1Qo8XX';

$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$connection->setApiVersion('1.1'); // Use v1.1 for all API calls
// $connection->setTimeouts(60, 15);

$content = $connection->get("account/verify_credentials"); //this code is use to checking connection
echo '<pre>';
print_r($content);exit;
// $connection->setApiVersion('2');
// echo '<pre>';
// print_r($statuses);exit;
$status = 'This is a test tweet from PHP!';
$post = $connection->post('statuses/update', ['status' => $status]);

if ($connection->getLastHttpCode() == 200) {
    echo 'Tweet posted successfully!';
    print_r($post);
} else {
    echo "Error posting tweet: " . $connection->getLastHttpCode();
    print_r($post);
}
exit;
echo '<pre>';
print_r($statues);exit;

if(isset($_POST['Submit'])){
    $location = $_FILES['media_image']['tmp_name'];
    if ($_FILES['media_image']['error'] !== UPLOAD_ERR_OK) {
        die('File upload failed with error code ' . $_FILES['media_image']['error']);
    }

    $fileInfo = getimagesize($location);
    if ($fileInfo === false) {
        die('Uploaded file is not a valid image.');
    }

    if (!file_exists($location) || !is_readable($location)) {
        die('Uploaded file does not exist or is not readable.');
    }
    
    $media = $connection->upload('media/upload', ['media' => $location]);
    $connection->setApiVersion(2);

    // if ($media === null) {
    //     echo "Failed to upload media. No response from Twitter.";
    // }

    if ($connection->getLastHttpCode() == 200) {
        echo 'Tweet posted successfully!';
        print_r($media);
    } else {
        echo "Error posting tweet: " . $connection->getLastHttpCode();
        print_r($media);
    }
    
    exit;

    
    var_dump($media); // Print response to check for errors
    
    if ($media && isset($media->media_id_string)) {
        $parameters = [
            'status' => 'Meow Meow Meow',
            'media_ids' => $media->media_id_string
        ];
        $result = $connection->post("statuses/update", $parameters);

        if ($connection->getLastHttpCode() == 200) {
            echo 'Tweet posted successfully!';
            print_r($result);
        } else {
            echo "Error posting tweet: " . $connection->getLastHttpCode();
            print_r($result);
        }
    } else {
        echo "Error uploading media.";
        print_r($media); // Print response to see why it failed
    }
}

?>

<form action="" method="post" enctype="multipart/form-data">
  <label for="fname">First name:</label><br>
  <input type="text" id="fname" name="fname" value="John"><br>
  <label>Choose Image<span class="text-danger">  </span></label><br>
  <input name="media_image" type="file" ><br>
  <input type="submit" name="Submit" value="Submit">
</form>
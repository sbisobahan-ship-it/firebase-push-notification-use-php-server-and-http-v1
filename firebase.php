<?php

$token = 'f_sx0x0bS5-XtCD9RK3Uw2:APA91bG8OEh43rDYVhmlj4rAfVN_qeRHmUtkgU87GUPMuVYOBWkMH51-Nolferrvzy11mYLg9MoZs1-6XPaN5jIYPiuYxim9j_FBGWhLq3-4Gq8YkXoOEKc';
$title = "কি খবর তোমাদের?";
$body = "ভালো আছি ভালো থেকো, আকাশের ঠিকানায় চিঠি লিখো........";

// Include the get-access-token.php
require 'get-access-token.php';

// Path to your service account key file
$serviceAccountKeyFile = 'service-account-file.json';

// Obtain the OAuth 2.0 Bearer Token
$accessToken = getAccessToken($serviceAccountKeyFile);

// Prepare message payload
$message = [
    'message' => [
        'token' => $token,
        'notification' => [
            'title' => $title,
            'body'  => $body
        ],
        'data' => [
            'extra_info' => 'ফ্রন্টএন্ড বা কাস্টম ডেটা এখানে যাবে'
        ],
        'android' => [
            'priority' => 'HIGH',
            'notification' => [
                'channel_id' => 'default_channel',
                'click_action' => 'OPEN_ACTIVITY_1'
            ]
        ]
    ]
];

// Encode to JSON
$json = json_encode($message);

// Setup cURL
$url = "https://fcm.googleapis.com/v1/projects/push-notification-73f28/messages:send";
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Send the request
$response = curl_exec($ch);
if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
}
curl_close($ch);

// Print response
echo $response;
?>
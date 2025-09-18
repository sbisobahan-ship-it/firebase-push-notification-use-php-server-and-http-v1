<?php

$token = 'FCM  টোকেন ( যা পাওয়া যাবে এন্ডোয়েড প্রজেক্ট এ ফাইয়ার বেজ এড করে নিয়ে টেস্ট হিসাবে লগক্যাট থেকে দেখা যায় (চ্যাট জিপিটিকে বলবে =  আমাকে FCM  টোকেন বের করার কোড করে দাও তা নিয়ে প্রজেক্ট রান করলে লগক্যাট এ পাওয়া যাবে ))';
$title = "পুশ নটিফিকেশন এর টাইটেল ";
$body = "পুশ নটিফিকেশন মেসেজ ";

// Include the get-access-token.php
// এখানে এই একই ফোল্ডারে get-access-token.php নামে এই ফাইলটি রাখতে হবে ( যা এই রিপোজিটোরিতে পোস্ট করা আছে )
require 'get-access-token.php';

// Path to your service account key file
// এটাও এই একই ফোল্ডারে রাখতে হবে এবং এটা পাওয়া যাবে ( ফাইয়ারবেজ প্রজেক্ট থেকে - Project settings - থেকে -Service acounts থেকে Genaret new privet key তে ক্লিক করলে একটি ফাইল ডাউনলোড হবে সেটাকে রি নেম করে এখানে ওই নাম অনুযায়ী দিতে হবে
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

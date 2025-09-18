# firebase-push-notification-use-php-server-and-http-v1

# firebase.php
# Device Token ও Message Content
$token = 'f_sx0x0bS5-XtCD9RK3Uw2:APA91bG8OEh43rDYVhmlj4rAfVN_qeRHmUtkgU87GUPMuVYOBWkMH51-Nolferrvzy11mYLg9MoZs1-6XPaN5jIYPiuYxim9j_FBGWhLq3-4Gq8YkXoOEKc';
$title = "কি খবর তোমাদের?";
$body = "ভালো আছি ভালো থেকো, আকাশের ঠিকানায় চিঠি লিখো........";

কী হচ্ছে:

$token হলো ডিভাইসের FCM token (যেটাতে notification যাবে)

$title ও $body হলো notification-এর মূল বিষয়বস্তু

কেন করা হয়েছে:

যেই ডিভাইসকে notification পাঠাতে হবে সেটাকে নির্দিষ্ট করার জন্য

Notification-এর টেক্সট content সেট করার জন্য
-----------------------------------------------------
# Access Token আনা
require 'get-access-token.php';
$serviceAccountKeyFile = 'service-account-file.json';
$accessToken = getAccessToken($serviceAccountKeyFile);


কী হচ্ছে:

get-access-token.php include করে নেওয়া হচ্ছে

Service Account JSON ফাইল থেকে JWT তৈরি → Google API থেকে OAuth2 Bearer Token আনা হচ্ছে

কেন করা হয়েছে:

FCM HTTP v1 API শুধুমাত্র Bearer Token দিয়ে authenticate হয়

তাই প্রথমে token আনা জরুরি
-------------------------------------------------------
# Message Payload তৈরি
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


কী হচ্ছে:

Notification payload তৈরি করা হয়েছে JSON-এর মতো structure-এ

তিনটি প্রধান অংশ:

token → যাকে পাঠাবেন

notification → UI display (title, body)

data → কাস্টম key-value pair, background processing এর জন্য

android → Android specific settings (priority, channel, click action)

কেন করা হয়েছে:

Notification কিভাবে দেখাবে, কোন activity খুলবে এবং background data কী থাকবে তা ঠিক করার জন্য

-------------------------------------------------------------------------------------------------
# cURL সেটআপ
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


কী হচ্ছে:

FCM API এর URL সেট করা হয়েছে

HTTP headers দিয়ে JSON content type এবং Bearer Token পাঠানো হচ্ছে

cURL options দিয়ে POST request প্রস্তুত

কেন করা হয়েছে:

HTTP POST request পাঠানোর জন্য cURL ব্যবহার হচ্ছে

Authorization সহ JSON body পাঠাতে
------------------------------------------------------------------------
# . get-access-token.php

কী কাজ করে:

Google Service Account থেকে JWT তৈরি করে

JWT দিয়ে OAuth 2.0 Access Token আনে

Access Token ১ ঘণ্টার জন্য বৈধ থাকে

কেন করা হয়েছে:

FCM HTTP v1 API ব্যবহার করতে Access Token প্রয়োজন

প্রতি নোটিফিকেশনের জন্য বারবার নতুন টোকেন না নিয়ে, cache করে বারবার ব্যবহার করা যায়

প্রধান ফাংশন:

generateJWT() → service account থেকে JWT তৈরি করে

getAccessToken() → JWT দিয়ে Google থেকে Access Token আনে

base64UrlEncode() → JWT-এর জন্য Base64 URL safe এনকোডিং করে



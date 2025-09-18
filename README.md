# firebase-push-notification-use-php-server-and-http-v1

#. get-access-token.php

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



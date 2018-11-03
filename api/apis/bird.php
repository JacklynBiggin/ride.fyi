<?php

$startPoint = htmlspecialchars($_GET['start']);
$endPoint = htmlspecialchars($_GET['end']);

$ch = curl_init();
$birdStartPoint = explode(',', $startPoint);
$birdEndPoint = explode(',', $endPoint);
curl_setopt($ch, CURLOPT_URL, "https://api.bird.co/user/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('Device-id: f0022387-1e81-4763-88db-ccdb894d8f86', 'Platform: ios', 'Content-type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"email": "test@test.com"}');
echo $birdResults = curl_exec($ch);
curl_close($ch);
$birdResults = json_decode($birdResults, true);
print_r($birdResults);

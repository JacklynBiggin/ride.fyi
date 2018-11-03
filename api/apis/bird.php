<?php

$startPoint = htmlspecialchars($_GET['start']);
$endPoint = htmlspecialchars($_GET['end']);
$deviceID = 'f0022387-1e81-4763-88db-ccdb894d8f86';
$ch = curl_init();
$birdStartPoint = explode(',', $startPoint);
$birdEndPoint = explode(',', $endPoint);
curl_setopt($ch, CURLOPT_URL, "https://api.bird.co/user/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('Device-id: '.$deviceID, 'Platform: ios', 'Content-type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"email": "'.time().'@example.com"}');
$birdResults = curl_exec($ch);
curl_close($ch);

$birdResultsAccessID = json_decode($birdResults, true)['token'];
// $birdResultsAccessID = 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJBVVRIIiwidXNlcl9pZCI6ImFiMjRhYzI0LWEyYzItNDNiNy05NDliLWU0YzRiNmQ3M2IzOSIsImRldmljZV9pZCI6ImYwMDIyMzg3LTFlODEtNDc2My04OGRiLWNjZGI4OTRkOGY4NiIsImV4cCI6MTU3Mjc2MzcxMX0.6HM1Mo5PMneztiqUYJLLUymRiS9T1KRoml7XmawOUEs';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.bird.co/bird/nearby?latitude=".$birdStartPoint[0]."&longitude=".$birdStartPoint[1]."&radius=1000");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('Authorization: Bird '.$birdResultsAccessID, 'Device-id: '.$deviceID, 'App-Version: 3.0.5', 'Location: {"latitude":'.$birdStartPoint[0].',"longitude":'.$birdStartPoint[1].',"altitude":500,"accuracy":100,"speed":-1,"heading":-1}'));
echo $birdResults = curl_exec($ch);
curl_close($ch);

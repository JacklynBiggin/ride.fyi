<?php
$uberStartPoint = explode(",", $startPoint);
$uberEndPoint = explode(",", $endPoint);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.uber.com/v1.2/estimates/price?start_latitude=" . $uberStartPoint[0] . "&start_longitude=" . $uberStartPoint[1] . "&end_latitude=" . $uberEndPoint[0] . "&end_longitude=" . $uberEndPoint[1]);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('Authorization: Token LBrDf-qBHkYEKAantefzX5CeTIiFMxPE2gn3bhNQ'));
$api_return = curl_exec($ch);
curl_close($ch);

echo $api_return;

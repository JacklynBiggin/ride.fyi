<?php
// $startPoint
// $endPoint
$ch = curl_init();
$startPointArray = explode(',', $startPoint);
$endPointArray = explode(',', $endPoint);
echo $startPointArray;
echo $endPointArray;
echo curl_setopt($ch, CURLOPT_URL, "https://api.lyft.com/v1/cost?start_lat=".$startPointArray[0]."&start_lng=".$startPointArray[1]."&end_lat=".$endPointArray[0]."&end_lng=".$startPointArray[1]);
// curl_setopt($s,CURLOPT_HTTPHEADER, array('Authorization: bearer <access_token>'));
curl_exec($ch);
curl_close($ch);

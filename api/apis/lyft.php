<?php
// $startPoint
// $endPoint
$ch = curl_init();
$lyftStartPoint = explode(',', $startPoint);
$lyftEndPoint = explode(',', $endPoint);
$lyftReturn = curl_setopt($ch, CURLOPT_URL, "https://api.lyft.com/v1/cost?start_lat=".$lyftStartPoint[0]."&start_lng=".$lyftStartPoint[1]."&end_lat=".$lyftEndPoint[0]."&end_lng=".$lyftEndPoint[1]);
// curl_setopt($s,CURLOPT_HTTPHEADER, array('Authorization: bearer <access_token>'));
curl_exec($ch);
curl_close($ch);

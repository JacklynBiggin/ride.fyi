<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://route.api.here.com/routing/7.2/calculateroute.json?app_id=" . $config['HERE_APP_ID'] . "&app_code=" . $config['HERE_APP_CODE'] . "&waypoint0=geo!" . $startPoint . "&waypoint1=" . $endPoint . "&mode=fastest;bicycle");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$bikeApiReturn = curl_exec($ch);
curl_close($ch);

if(strpos($bikeApiReturn, 'ApplicationError')) {
  return;
}

$bikeApiReturn = json_decode($bikeApiReturn, true);

$bikeDistance = round(0.000621371 * $bikeApiReturn['response']['route'][0]['summary']['distance'], 2);
array_push($results, array('currency' => false, 'price' => 0, 'name' => 'Bike', 'distance' => $bikeDistance, 'time' => $bikeApiReturn['response']['route'][0]['summary']['baseTime']));

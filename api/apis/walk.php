<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://route.api.here.com/routing/7.2/calculateroute.json?app_id=" . $config['HERE_APP_ID'] . "&app_code=" . $config['HERE_APP_CODE'] . "&waypoint0=geo!" . $startPoint . "&waypoint1=" . $endPoint . "&mode=fastest;pedestrian");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$walkApiReturn = curl_exec($ch);
curl_close($ch);

// This is pretty horrible error handling, but don't display if not available
if(strpos($walkApiReturn, 'ApplicationError')) {
  return;
}

$walkApiReturn = json_decode($walkApiReturn, true);

$walkDistance = round(0.000621371 * $walkApiReturn['response']['route'][0]['summary']['distance'], 2);
array_push($results, array('currency' => false, 'price' => 0, 'name' => 'Walking', 'distance' => $walkDistance, 'time' => $walkApiReturn['response']['route'][0]['summary']['baseTime']));

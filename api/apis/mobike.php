<?php
$mobikeStartPoint = explode(',', $startPoint);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://mwx.mobike.com/mobike-api/rent/nearbyBikesInfo.do?latitude=" . $mobikeStartPoint[0] . "&longitude=" . $mobikeStartPoint[1]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('content-type: application/x-www-form-urlencoded', 'user-agent: Mozilla/5.0 (Linux; Android 7.0; SM-G892A Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/60.0.3112.107 Mobile Safari/537.36'));
$mobikeResults = curl_exec($ch);
curl_close($ch);

if(strpos($mobikeResults, '当前区域未开通') || strpos($mobikeResults, 'object":[],')) {
  return;
}

$mobikeResults = json_decode($mobikeResults, true);

$bikeLocation = $mobikeResults['object'][0]['distY'] . "," . $mobikeResults['object'][0]['distX'];

/* Now lets calculate how long it takes to walk there */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://route.api.here.com/routing/7.2/calculateroute.json?app_id=" . $config['HERE_APP_ID'] . "&app_code=" . $config['HERE_APP_CODE'] . "&waypoint0=geo!" . $startPoint . "&waypoint1=" . $bikeLocation . "&mode=fastest;pedestrian");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$walkApiReturn = curl_exec($ch);
curl_close($ch);

$walkApiReturn = json_decode($walkApiReturn, true);
$mobikeDistance = round(0.000621371 * $walkApiReturn['response']['route'][0]['summary']['distance'], 2);
$mobikeTime = $walkApiReturn['response']['route'][0]['summary']['baseTime'];


/* And now get the bike directions */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://route.api.here.com/routing/7.2/calculateroute.json?app_id=" . $config['HERE_APP_ID'] . "&app_code=" . $config['HERE_APP_CODE'] . "&waypoint0=geo!" . $bikeLocation . "&waypoint1=" . $endPoint . "&mode=fastest;bicycle");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$bikeApiReturn = curl_exec($ch);
curl_close($ch);

if(strpos($bikeApiReturn, 'ApplicationError')) {
  return;
}
$bikeApiReturn = json_decode($bikeApiReturn, true);

$mobikePrice = ceil($bikeApiReturn['response']['route'][0]['summary']['travelTime'] / 60 / 15); // Mobike costs $1 per 15 mins
$mobikeTime = $mobikeTime + $bikeApiReturn['response']['route'][0]['summary']['travelTime'];
$mobikeDistance = $mobikeDistance + round(0.000621371 * $bikeApiReturn['response']['route'][0]['summary']['distance'], 2);

array_push($results, array('currency' => "USD", 'price' => $mobikePrice, 'name' => 'Mobike', 'distance' => $mobikeDistance, 'time'=> $mobikeTime));

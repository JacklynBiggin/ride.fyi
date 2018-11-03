<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://route.api.here.com/routing/7.2/calculateroute.json?app_id=jPeL8FxtgBOBIB9ISZPZ&app_code=mXQv9GX6ULnbMkeJYR2rVQ&waypoint0=geo!" . $startPoint . "&waypoint1=" . $endPoint . "&mode=fastest;bicycle");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$bikeApiReturn = curl_exec($ch);
curl_close($ch);


$bikeApiReturn = json_decode($bikeApiReturn, true);

$bikeDistance = round(0.000621371 * $bikeApiReturn['response']['route'][0]['summary']['distance'], 2);
array_push($results, array('currency' => false, 'name' => 'Bike', 'distance' => $bikeDistance, 'time' => $bikeApiReturn['response']['route'][0]['summary']['baseTime']));

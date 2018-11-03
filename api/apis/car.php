<?php

// Get routing for car
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://route.api.here.com/routing/7.2/calculateroute.json?app_id=jPeL8FxtgBOBIB9ISZPZ&app_code=mXQv9GX6ULnbMkeJYR2rVQ&waypoint0=geo!" . $startPoint . "&waypoint1=" . $endPoint . "&mode=fastest;car");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$carApiReturn = curl_exec($ch);
curl_close($ch);

$carApiReturn = json_decode($carApiReturn, true);

// Get petrol pricing for local sqlite_create_aggregate
$splitStartPoint = explode(',', $startPoint);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.mygasfeed.com/stations/radius/" . $splitStartPoint[0] . "/" . $splitStartPoint[1] . "/20/reg/price/zny0upy6mo.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$carPetrolPricing = curl_exec($ch);
curl_close($ch);

// If petrol pricing is unavailable, don't work out
if(empty($carPetrolPricing)) {
  exit;
}

$carPetrolPricing = json_decode($carPetrolPricing, true);
$petrolPrice = "";

$i = 0;
while($i <= sizeof($carPetrolPricing['stations'])) {
  if($petrolPrice == "") {
    if($carPetrolPricing['stations'][$i]['reg_price'] !== 'N/A') {
      $petrolPrice = $carPetrolPricing['stations'][$i]['reg_price'];
    }
  }

  $i++;
}

// Exit gracefully if there's STILL no petrol prices
if($petrolPrice == "") {
  exit;
}

$carDistance = round(0.000621371 * $carApiReturn['response']['route'][0]['summary']['distance'], 2);
array_push($results, array('currency' => "USD", 'price' => $petrolPrice, 'name' => 'Car', 'distance' => $carDistance, 'time' => $carApiReturn['response']['route'][0]['summary']['baseTime']));
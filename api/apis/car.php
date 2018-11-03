<?php
function getCar($startPoint, $endPoint) {
  global $config;
  // Get routing for car
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://route.api.here.com/routing/7.2/calculateroute.json?app_id=" . $config['HERE_APP_ID'] . "&app_code=" . $config['HERE_APP_CODE'] . "&waypoint0=geo!" . $startPoint . "&waypoint1=" . $endPoint . "&mode=fastest;car");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $carApiReturn = curl_exec($ch);
  curl_close($ch);

  if(strpos($carApiReturn, 'ApplicationError')) {
    return;
  }

  $carApiReturn = json_decode($carApiReturn, true);

  // Get petrol pricing for local sqlite_create_aggregate
  $splitStartPoint = explode(',', $startPoint);

  $url = "http://api.mygasfeed.com/stations/radius/" . urlencode($splitStartPoint[0]) . "/" . urlencode($splitStartPoint[1]) . "/20/reg/price/zny0upy6mo.json";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $carPetrolPricing = curl_exec($ch);
  curl_close($ch);

  // If petrol pricing is unavailable, don't work out
  if(empty($carPetrolPricing)) {
    exit;
  }

  $petrolPricesAvailable = true;
  if(strpos($carPetrolPricing, 'description":"none')) {
    $petrolPricesAvailable = false;
  }

  $carPetrolPricing = json_decode($carPetrolPricing, true);

  $petrolPrice = "";


  if($petrolPricesAvailable) {
    $i = 0;
    while($i <= sizeof($carPetrolPricing['stations'])) {
      if($petrolPrice == "") {
        if($carPetrolPricing['stations'][$i]['reg_price'] !== 'N/A') {
          $petrolPrice = $carPetrolPricing['stations'][$i]['reg_price'];
        }
      }

      $i++;
    }
  }

  // Exit gracefully if there's STILL no petrol prices
  if($petrolPrice == "" && $petrolPricesAvailable) {
    exit;
  }

  $carDistance = round(0.000621371 * $carApiReturn['response']['route'][0]['summary']['distance'], 2);
  if($petrolPricesAvailable) {
    return array('currency' => "USD", 'price' => $petrolPrice, 'name' => 'Car', 'distance' => $carDistance, 'time' => $carApiReturn['response']['route'][0]['summary']['baseTime']);
  } else {
    return array('prices_unavailable' => true, 'currency' => "USD", 'price' => '0', 'name' => 'Car', 'distance' => $carDistance, 'time' => $carApiReturn['response']['route'][0]['summary']['baseTime']);
  }
}

<?php
$time = urlencode(date('Y-m-d').'T'.date('H:i:s'));
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://transit.api.here.com/v3/route.json?app_id=" . $config['HERE_APP_ID'] . "&app_code=" . $config['HERE_APP_CODE'] . "&routing=all&dep=" . $startPoint . "&arr=" . $endPoint . "&time=".$time);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$transitResult = curl_exec($ch);
curl_close($ch);

if(strpos($transitResult, 'ApplicationError')) {
  return;
}

$transitResult = json_decode($transitResult, true)['Res']['Connections']['Connection'];
foreach ($transitResult as $pathOption) {
  // require 'hybrid.php';
  $startTime = date_create_from_format('Y-m-d\TH:i:s', $pathOption['Dep']['time']);
  $endTime = date_create_from_format('Y-m-d\TH:i:s', $pathOption['Arr']['time']);
  $tripTime = abs($startTime->getTimestamp()-$endTime->getTimestamp());
  $currentPath = $pathOption['Sections']['Sec'];
  $name;
  $totalDistanceAcrossPaths = 0;
  foreach ($currentPath as $section) {
    if (isset($section['Dep']['Stn']) and isset($section['Arr']['Stn'])) {
      $name = $section['Dep']['Stn']['name'].' to '.$section['Arr']['Stn']['name']; //Bases name off last path
    }
    $totalDistanceAcrossPaths += calcStopsIfExists($section['Journey']);
  }
  $totalDistanceAcrossPaths = round($totalDistanceAcrossPaths, 2);
  $resultsAppend = array('name' => 'Transit Route' . (isset($name) ? ' for '.$name : ''), 'distance' => $totalDistanceAcrossPaths, 'currency' => isset($pathOption['Tariff']['Fares']['0']['Fare']['0']['currency']) ? $pathOption['Tariff']['Fares']['0']['Fare']['0']['currency'] : 'USD',
    'price' => isset($pathOption['Tariff']['Fares']['0']['Fare']['0']['price']) ? $pathOption['Tariff']['Fares']['0']['Fare']['0']['price'] : '0', 'time' => $tripTime);
  if(empty($pathOption['Tariff']['Fares']['0']['Fare']['0']['price'])){
    $resultsAppend['prices_unavailable'] = 'true';
  }
  // require 'hybrid.php';
  array_push($results, $resultsAppend);
}

function getDistance($lat1, $lon1, $lat2, $lon2) {
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  return $miles;

}

function calcStopsIfExists($journeyArray){
  if(isset($journeyArray['distance'])) {
    return metersToMiles($journeyArray['distance']);
  } elseif (isset($journeyArray['Stop'])) {
    $totalDistance = 0;
    $prev = $journeyArray['Stop'][0];
    $journeyArray['Stop'] = array_slice($journeyArray['Stop'], 1);
    foreach ($journeyArray['Stop'] as $stop) {
      $totalDistance += getDistance($prev['Stn']['y'], $prev['Stn']['x'], $stop['Stn']['y'], $stop['Stn']['x']);
      $prev = $stop;
    }
    return $totalDistance;
  }
  return "error with stop calc";
}
function metersToMiles($meters){
  return 0.000621371 * $meters;
}

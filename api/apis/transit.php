<?php
function getAllTransits($startPoint, $endPoint, $hybridStage, $timestamp) {
  global $config;

  if (empty($timestamp)) {
    $time = urlencode(date('Y-m-d').'T'.date('H:i:s'));
  } else {
    $time = urlencode(date('Y-m-d', $timestamp).'T'.date('H:i:s', $timestamp));
  }

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://transit.api.here.com/v3/route.json?app_id=" . $config['HERE_APP_ID'] . "&app_code=" . $config['HERE_APP_CODE'] . "&routing=all&dep=" . $startPoint . "&arr=" . $endPoint . "&time=".$time);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $transitResult = curl_exec($ch);
  curl_close($ch);

  if(strpos($transitResult, 'ApplicationError') || strpos($transitResult, '"text":"Out of coverage"')) {
    return [null];
  }

  $transitResult = json_decode($transitResult, true)['Res']['Connections']['Connection'];
  $allResults = [];
  $nonBestHybridResults = [];
  foreach ($transitResult as $pathOption) {
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
    if($hybridStage == 0) {
      $hybrids = getAllHybrids($startPoint, $endPoint, $pathOption);
      if(!empty($hybrids)){
        $nonBestHybridResults = array_merge($nonBestHybridResults, $hybrids);
      }
    }
    $allResults[] = $resultsAppend;
  }
  if (!empty($allResults)) {
    $databaseSQL = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$databaseSQL) { //Triggered if databaseSQL is null and shows error
      trigger_error('Could not connect to MySQL: '.mysqli_connect_error());
    }
    $create_appt = mysqli_prepare($databaseSQL, "INSERT INTO requests (UnixTimestamp, latlong, latlongend, ratio) VALUES (?,?,?,?);");
    $ratio = 0.0;
    mysqli_stmt_bind_param($create_appt, 'issd', $timestamp, $startPoint, $endPoint, $ratio);
    foreach ($allResults as $databaseResult) {
      $ratio = $databaseResult['distance']/$databaseResult['time'];
      if (!mysqli_stmt_execute($create_appt)) {
        echo '<p>Please try again. An error occured. Your confirmation is invalid.</p>';
        exit();
      }
    }
    mysqli_stmt_close($create_appt);

  }
  if(!empty($nonBestHybridResults)){
    $allResults = array_merge(bestHybrid($nonBestHybridResults), $allResults);
  }
  return $allResults;
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

<?php
$config['HERE_APP_ID'] = 'jPeL8FxtgBOBIB9ISZPZ';
$config['HERE_APP_CODE'] = 'mXQv9GX6ULnbMkeJYR2rVQ';
$results = [];

$startPoint = htmlspecialchars($_GET['start']);
$endPoint = htmlspecialchars($_GET['end']);
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
print_r($transitResult);
foreach ($transitResult as $pathOption) {
  $startTime = date_create_from_format('Y-m-d\TH:i:s', $pathOption['Dep']['time']);
  $endTime = date_create_from_format('Y-m-d\TH:i:s', $pathOption['Arr']['time']);
  $tripTime = abs($startTime->getTimestamp()-$endTime->getTimestamp());
  $currentPath = $pathOption['Sections']['Sec'];
  foreach ($currentPath as $section) {

    // if (isset($section['Dep']['Stn']) and isset($section['Dep']['Stn'])) {
    //
    // } else {
    //
    // }
    //Might need for loop to add up fares
  }

  array_push($results, array('name' => 'Transit Route', 'distance' => 'null', 'currency' => $pathOption['Tariff']['Fares']['0']['Fare']['0']['currency'],
    'price' => $pathOption['Tariff']['Fares']['0']['Fare']['0']['price'], 'time' => $tripTime));
}

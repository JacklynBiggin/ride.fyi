<?php
$uberStartPoint = explode(",", $startPoint);
$uberEndPoint = explode(",", $endPoint);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.uber.com/v1.2/estimates/price?start_latitude=" . $uberStartPoint[0] . "&start_longitude=" . $uberStartPoint[1] . "&end_latitude=" . $uberEndPoint[0] . "&end_longitude=" . $uberEndPoint[1]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('Authorization: Token LBrDf-qBHkYEKAantefzX5CeTIiFMxPE2gn3bhNQ'));
$uberApiReturn = curl_exec($ch);
curl_close($ch);

$uberApiReturn = json_decode($uberApiReturn, true);

foreach($uberApiReturn['prices'] as $transport) {
  $transportName = 'Uber ' . $transport['localized_display_name'];
  $transportPrice = $transport['high_estimate'] + $transport['low_estimate'] / 2;
  
  if($transportPrice !== 0) { // If a service isn't available, Uber says the price is 0
    array_push($results, array('name' => $transportName, 'distance' => $transport['distance'], 'currency' => $transport['currency_code'], 'price' => $transportPrice));
  }
}

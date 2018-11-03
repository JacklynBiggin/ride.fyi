<?php
//Can remove later
$startPoint = htmlspecialchars($_GET['start']);
$endPoint = htmlspecialchars($_GET['end']);

$ch = curl_init();
$lyftStartPoint = explode(',', $startPoint);
$lyftEndPoint = explode(',', $endPoint);
curl_setopt($ch, CURLOPT_URL, "https://api.lyft.com/v1/cost?start_lat=".$lyftStartPoint[0]."&start_lng=".$lyftStartPoint[1]."&end_lat=".$lyftEndPoint[0]."&end_lng=".$lyftEndPoint[1]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$lyftReturn = curl_exec($ch);
curl_close($ch);
$lyftReturn = json_decode($lyftReturn, true);
$lyftReturn = $lyftReturn['cost_estimates'];
foreach ($lyftReturn as $currentCarType) {
  // print_r($currentCarType);
  if (strpos($currentCarType['display_name'], 'Lyft') === false) {
    $currentCarType['display_name'] = 'Lyft ' . $currentCarType['display_name'];
  }
  $avgCostDollars = (($currentCarType['estimated_cost_cents_max']+$currentCarType['estimated_cost_cents_min'])/2)/100;
  $results[] = ["name"=>$currentCarType['display_name'],"price"=>$avgCostDollars,"currency"=>$currentCarType['currency'],"time"=>$currentCarType['estimated_duration_seconds'],"distance"=>$currentCarType['estimated_distance_miles']];
}
// print_r($lyftReturn);

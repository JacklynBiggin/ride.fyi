<?php
function getAllLyfts($startPoint, $endPoint) {
  global $config;
  $ch = curl_init();
  $lyftStartPoint = explode(',', $startPoint);
  $lyftEndPoint = explode(',', $endPoint);
  curl_setopt($ch, CURLOPT_URL, "https://api.lyft.com/v1/cost?start_lat=".$lyftStartPoint[0]."&start_lng=".$lyftStartPoint[1]."&end_lat=".$lyftEndPoint[0]."&end_lng=".$lyftEndPoint[1]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $lyftReturn = curl_exec($ch);
  curl_close($ch);

  if(strpos($lyftReturn, 'error_description')) {
    return;
  }

  $lyftReturn = json_decode($lyftReturn, true);
  $lyftReturn = $lyftReturn['cost_estimates'];
  $results = [];
  foreach ($lyftReturn as $currentCarType) {
    // print_r($currentCarType);
    if (strpos($currentCarType['display_name'], 'Lyft') === false) {
      $currentCarType['display_name'] = 'Lyft ' . $currentCarType['display_name'];
    }
    $avgCostDollars = (($currentCarType['estimated_cost_cents_max']+$currentCarType['estimated_cost_cents_min'])/2)/100;
    $results[] = array("name"=>$currentCarType['display_name'],"price"=>$avgCostDollars,"currency"=>$currentCarType['currency'],"time"=>$currentCarType['estimated_duration_seconds'],"distance"=>$currentCarType['estimated_distance_miles']);
  }
  return $results;
}

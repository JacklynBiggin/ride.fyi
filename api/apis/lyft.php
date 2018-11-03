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
  print_r($currentCarType);
  echo "<br>";
  echo "<br>";
  echo "<br>";
  echo "<br>";
}
$results[] = ["name"=>"Lyft","price"=>"test","currency"=>"test","time"=>"test"];
// print_r($lyftReturn);

<?php
require_once "./config.php";

$query = urlencode(htmlspecialchars($_GET['query']));
$queryUrl = "https://geocoder.api.here.com/6.2/geocode.json?app_id=" . $config['HERE_APP_ID'] . "&app_code=" . $config['HERE_APP_CODE'] . "&searchtext=" . $query;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $queryUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

header('Content-Type: application/json');
if(strpos($response, '},"View":[]}}')) {
  $return = array("status" => "error", "message" => "Sorry, that location doesn't seem to exist.");
  echo json_encode($return);
  exit;
}

$response = json_decode($response, true);

$coords = $response['Response']['View'][0]['Result'][0]['Location']['NavigationPosition'][0]['Latitude'] . "," . $response['Response']['View'][0]['Result'][0]['Location']['NavigationPosition'][0]['Longitude'];
$return = array("status" => "success", "location_name" => $response['Response']['View'][0]['Result'][0]['Location']['Address']['Label'], "coords" => $coords);
echo json_encode($return);

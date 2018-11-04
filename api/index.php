<?php
// Fail gracefullyish if the coordinates don't exist are are invalid
if(
  !isset($_GET['start']) or
  !isset($_GET['end']) or
  empty($_GET['start']) or
  empty($_GET['end']) or
  !coordinatesAreValid($_GET['start']) or
  !coordinatesAreValid($_GET['end'])
) {
  http_response_code(400);
  exit;
}
// http://10.67.173.53/vandyhacks5/api/index.php?start=37.7763,-122.3918&end=37.7972,-122.4533

//local access: http://10.67.173.53/vandyhacks5/api/apis/bird.php?start=36.143036,-86.805698&end=36.165701,-86.784200

$startPoint = htmlspecialchars($_GET['start']);
$endPoint = htmlspecialchars($_GET['end']);

$results = [];
// For each result, include name, price, currency, time (seconds), distance (miles)
require_once "./config.php";

require_once './apis/uber.php';
require_once './apis/lyft.php';
require_once './apis/bird.php';
require_once './apis/mobike.php';
require_once './apis/bike.php';
require_once './apis/walk.php';
require_once './apis/car.php';
require_once './apis/transit.php';
require_once './apis/hybrid.php';

array_push($results, getBird($startPoint, $endPoint));
array_push($results, getWalk($startPoint, $endPoint));
array_push($results, getMobike($startPoint, $endPoint));
array_push($results, getBike($startPoint, $endPoint));
array_push($results, getCar($startPoint, $endPoint));
$results = array_merge($results, getAllLyfts($startPoint, $endPoint));
$results = array_merge($results, getAllUbers($startPoint, $endPoint));
$results = array_merge($results, getAllTransits($startPoint, $endPoint, 0, null)); //Includes hybrids

$results = array_filter($results); //Removes null results
// Now lets sort there results - isn't that wonderful?
array_multisort(array_column($results, 'price'), SORT_ASC,
                array_column($results, 'time'), SORT_ASC,
$results);
header('Content-Type: application/json');
echo json_encode($results);
function coordinatesAreValid ($coordinate) {
  $coordinateArray = explode(',', $coordinate);
  //NEED TO FIX
  // $checkRegex = preg_match('/?-[0-9\.]+,?-[0-9\.]+/', $coordinate);
  return true;
  // https://stackoverflow.com/questions/15965166/what-is-the-maximum-length-of-latitude-and-longitude

}

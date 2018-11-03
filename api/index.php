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
$startPoint = htmlspecialchars($_GET['start']);
$endPoint = htmlspecialchars($_GET['end']);

require_once './apis/uber.php';
require_once './apis/lyft.php';

function coordinatesAreValid ($coordinate) {
  $coordinateArray = explode(',', $coordinate);
  //NEED TO FIX
  // $checkRegex = preg_match('/?-[0-9\.]+,?-[0-9\.]+/', $coordinate);
  return true;
  // https://stackoverflow.com/questions/15965166/what-is-the-maximum-length-of-latitude-and-longitude

}


//type/company, time,price ?route?
// require_once '/apis/lyft.php';

/*
  {
    "type": "Uber",
    "price": "5",
    "time": "107",
    "route": "???"
  }
*/

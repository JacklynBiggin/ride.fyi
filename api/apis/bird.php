<?php
function getBird($startPoint, $endPoint) {
  global $config;
  $birdStartPoint = explode(',', $startPoint);
  $birdEndPoint = explode(',', $endPoint);
  //Needs to include walking error checking

  //Generates bird API access token
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.bird.co/user/login");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch,CURLOPT_HTTPHEADER, array('Device-id: '.$config['BIRD_PRICING']['birdGUID'], 'Platform: ios', 'Content-type: application/json'));
  curl_setopt($ch, CURLOPT_POSTFIELDS, '{"email": "'.time().'@example.com"}');
  //Currently generates new "email" for each request - should change to keep key in session and only refetch when expires
  $birdResults = curl_exec($ch);
  curl_close($ch);

  $birdResultsAccessID = json_decode($birdResults, true)['token'];

  //Uses token to find locations of nearby birds
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.bird.co/bird/nearby?latitude=".$birdStartPoint[0]."&longitude=".$birdStartPoint[1]."&radius=1000");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch,CURLOPT_HTTPHEADER, array('Authorization: Bird '.$birdResultsAccessID, 'Device-id: '.$config['BIRD_PRICING']['birdGUID'], 'App-Version: 3.0.5', 'Location: {"latitude":'.$birdStartPoint[0].',"longitude":'.$birdStartPoint[1].',"altitude":500,"accuracy":100,"speed":-1,"heading":-1}'));
  $birdResults = curl_exec($ch);
  $birdResultsRaw = $birdResults;
  curl_close($ch);
  $birdResults = json_decode($birdResults, true);

  if (strpos($birdResultsRaw, "Location is not a valid Location") || sizeof($birdResults['birds']) == 0) {
    // echo "No birds avaliable";
    return;
  }
  $closestBirdLocation = $birdResults['birds']['0']['location'];

  //Calculates time and distance to walk to Bird
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://route.api.here.com/routing/7.2/calculateroute.json?app_id=".$config['HERE_APP_ID']."&app_code=".$config['HERE_APP_CODE']."&waypoint0=geo!" . $startPoint . "&waypoint1=" .$closestBirdLocation['latitude'] . ',' .$closestBirdLocation['longitude'] . "&mode=fastest;pedestrian");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $walkToBird = json_decode(curl_exec($ch), true);
  curl_close($ch);
  $birdWalkDistance = round(0.000621371 * $walkToBird['response']['route'][0]['summary']['distance'], 2);
  $birdWalkTime = $walkToBird['response']['route'][0]['summary']['baseTime'];

  //Calculates time, distance, and price to ride Bird to destination
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://route.api.here.com/routing/7.2/calculateroute.json?app_id=".$config['HERE_APP_ID']."&app_code=".$config['HERE_APP_CODE']."&waypoint0=geo!" . $closestBirdLocation['latitude']. ',' .$closestBirdLocation['longitude'] . "&waypoint1=" . $endPoint . "&mode=fastest;pedestrian");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $birdRideResult = json_decode(curl_exec($ch), true);
  curl_close($ch);
  $birdRideDistance = 0.000621371 * $birdRideResult['response']['route'][0]['summary']['distance'];
  $birdRideTime = ($birdRideDistance/$config['BIRD_PRICING']['birdSpeed'])*360;
  $birdPrice = ($birdRideTime/60)*$config['BIRD_PRICING']['birdPerMinuteCost'];
  $birdPrice += $config['BIRD_PRICING']['birdFlatFare'];
  //Final results push
  // array_push($results, array('name' => 'Bird', 'distance' => round(($birdRideDistance + $birdWalkDistance), 2), 'currency' => 'USD', 'price' => round($birdPrice, 2), 'time' => round(($birdWalkTime + $birdRideTime), 2)));
  return array('name' => 'Bird', 'distance' => round(($birdRideDistance + $birdWalkDistance), 2), 'currency' => 'USD', 'price' => round($birdPrice, 1), 'time' => round(($birdWalkTime + $birdRideTime), 2));
}

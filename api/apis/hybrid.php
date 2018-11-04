<?php
function getAllHybrids($startPoint, $endPoint, $pathOption) {
  // echo "<h1>At least this   ".$pathOption['transfers'] . "</h1>";
  // print_r($pathOption);
  if ($pathOption['transfers'] > 1 and count($pathOption['Sections']['Sec']) > 4) { //Prevents errors and makes sure hybrid should even run
    $countNon20 = 0;
    $hybridResults = [];
    // echo "<h2>Here I am</h2>";
    foreach ($pathOption['Sections']['Sec'] as $step) {
      // echo "<h3>Here I am</h3>";
      if ($step['mode'] !== 20) {
        $countNon20++;
      }
      if ($countNon20 > 1 and empty($journeyArray['distance'])){
        $newStart = $startPoint;
        $newEnd = $step['Journey']['Stop']['0']['Stn']['y'].','.$step['Journey']['Stop']['0']['Stn']['x'];
        $subResults = [];
        array_push($subResults, getBird($newStart, $newEnd));
        array_push($subResults, getMobike($newStart, $newEnd));
        $subResults = array_merge($subResults, getAllLyfts($newStart, $newEnd));
        $subResults = array_merge($subResults, getAllUbers($newStart, $newEnd));
        $subResults = array_filter($subResults); //Removes null results
        foreach ($subResults as $subResult) {
          if ($subResult['name'] == 'Uber UberX' or $subResult['name'] == 'Lyft' or $subResult['name'] == 'Bird') {
            $newTransitOptions = getAllTransits($newEnd, $endPoint, 1, time()+$subResult['time']);
            foreach ($newTransitOptions as $newTransitOption) {
              $collectiveTime = $newTransitOption['time'] + $subResult['time'];
              $collectiveName = $subResult['name'].' with '.$newTransitOption['name'];
              $collectivePrice = $subResult['price'] + 2; //+ $newTransitOption['price'] //Adding two for transit cost
              $collectiveDistance = $subResult['distance'] + $newTransitOption['distance'];
              $hybridResults[] = array('currency' => $subResult['currency'],'time' => round($collectiveTime, 2), 'name' => $collectiveName, 'price' => round($collectivePrice, 2), 'distance' => round($collectiveDistance, 2));
            }
          }
        }
        return $hybridResults;

        // $hybridResults = [];
        // foreach ($subResults as $result) {
        //   $hybridResults = array('currency' => $result['currency'], 'price' => ($resultsAppend['price'] + $result['price']),
        //     'name' => $result['name'].' with '.$resultsAppend['name'], 'distance' => $resultsAppend['distance'], 'time' => 'test'));
        //   if (!(empty($resultsAppend['prices_unavailable']))) {
        //     $hybridResults['prices_unavailable'] = 'true';
        //   }
        // }
        // array_push($results, );
        // break 2;
        //add to results: VARIOUSRESULTS to $step.location from normal start
      }

    }
    // $reversedPathOption = array_reverse($pathOption, true);
  }
}
function bestHybrid($hybridResults) {
  $minTime = 100000; $minCost = 100000; $minTimeResult; $minCostResult;
  foreach ($hybridResults as $result) {
    if($result['time'] < $minTime) {
      $minTimeResult = $result;
      $minTime = $result['time'];
    }
    if($result['price'] < $minCost) {
      $minCostResult = $result;
      $minTime  = $result['price'];
    }
  }
  if ($minCostResult === $minTimeResult) {
    $results = [];
    $results[] = $minCostResult;
    return $results;
  } else {
    $results = [];
    $results[] = $minCostResult;
    $results[] = $minTimeResult;
    return $results;
    //Could check too see if seconds apart and not worth the comaprison, but too much work
  }
}
//only run if transfers > 1 and count of Sec > 4
//add results: take uber,lyft,bird,mobike from start to second mode != 20,
//add results: take uber,lyft,bird,mobike from second-to-last !=20 to end

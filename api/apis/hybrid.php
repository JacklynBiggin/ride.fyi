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
          if (preg_match('/(Black)(Select)(Lux)/',$subResult['name'])) {
            continue;
          }
          $newTransitOptions = getAllTransits($startPoint, $endPoint, 1, time()+$subResult['time']);
          foreach ($newTransitOptions as $newTransitOption) {
            $current
            $hybridResults[] = array('time' => $newTransitOption['time'] + $subResult['time'], 'name' => $subResult['name'].' with '.$newTransitOption['name']);
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
//only run if transfers > 1 and count of Sec > 4
//add results: take uber,lyft,bird,mobike from start to second mode != 20,
//add results: take uber,lyft,bird,mobike from second-to-last !=20 to end

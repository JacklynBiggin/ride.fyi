<?php
if ($pathOption['transfers'] > 1 and count($pathOption['Sections']['Sec']) > 4) { //Prevents errors and makes sure hybrid should even run
  $countNon20 = 0;
  foreach ($pathOption['Sections']['Sec'] as $step) {
    if ($step['mode'] !== 20) {
      $countNon20++;
    }
    if ($countNon20 == 2 and empty($journeyArray['distance'])){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "../index.php/?start=".$startPoint."&end=".$step['Journey']['Stop']['0']['Stn']['y'].','.$step['Journey']['Stop']['0']['Stn']['x']."&transit=true");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $subResults = curl_exec($ch);
      curl_close($ch);
      $subResults = json_decode($subResults, true);
      $hybridResults = [];
      foreach ($subResults as $result) {
        $hybridResults = array('currency' => $result['currency'], 'price' => ($resultsAppend['price'] + $result['price']),
          'name' => $result['name'].' with '.$resultsAppend['name'], 'distance' => $resultsAppend['distance'], 'time' => 'test'));
        if (!(empty($resultsAppend['prices_unavailable']))) {
          $hybridResults['prices_unavailable'] = 'true';
        }
      }
      array_push($results, );
      break 2;
      //add to results: VARIOUSRESULTS to $step.location from normal start
    }

  }
  $reversedPathOption = array_reverse($pathOption, true);
}
//only run if transfers > 1 and count of Sec > 4
//add results: take uber,lyft,bird,mobike from start to second mode != 20,
//add results: take uber,lyft,bird,mobike from second-to-last !=20 to end

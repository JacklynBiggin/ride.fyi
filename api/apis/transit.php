<?php
$startPoint = htmlspecialchars($_GET['start']);
$endPoint = htmlspecialchars($_GET['end']);
$time = urlencode(date('Y-m-d').'T'.date('H:i:s'));
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://transit.api.here.com/v3/route.json?app_id=" . $config['HERE_APP_ID'] . "&app_code=" . $config['HERE_APP_CODE'] . "&routing=all&dep=" . $startPoint . "&arr=" . $endPoint . "&time=".$time);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$transitResult = curl_exec($ch);
curl_close($ch);
$transitResult = json_decode($transitResult, true)['Res']['Connections']['Connection'];
$value = 0;
$transitResult = $transitResult[$value]['Sections']['Sec'];
foreach ($transitResult as $section) {

}

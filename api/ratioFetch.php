<?php
require_once "./config.php";
if(
  !isset($_GET['start']) or
  empty($_GET['start'])
) {
  http_response_code(400);
  exit;
}
$startPoint = htmlspecialchars($_GET['start']);
$radius = empty($_GET['radius']) ? 0.1 : htmlspecialchars($_GET['radius']);
$StartPoint = explode(",", $startPoint);
// $EndPoint = explode(",", $endPoint);

$values = array($StartPoint[0]-$radius, $StartPoint[0]+$radius, $StartPoint[1]-$radius, $StartPoint[1]+$radius);
$databaseSQL = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (!$databaseSQL) { //Triggered if databaseSQL is null and shows error
  trigger_error('Could not connect to MySQL: '.mysqli_connect_error());
}
$check_appt = mysqli_prepare($databaseSQL, "SELECT AVG(ratio) FROM requests WHERE (lat BETWEEN ? AND ?) AND (lon BETWEEN ? and ?);");
//SELECT AVG(ratio) FROM requests WHERE (lat BETWEEN 39.218103 AND 39.418103) AND (lon BETWEEN -86.905698 and -86.705698)
//39.34477,-76.691991
mysqli_stmt_bind_param($check_appt, 'dddd', $values[0], $values[1], $values[2], $values[3]);
mysqli_stmt_execute($check_appt);
$result = mysqli_fetch_all(mysqli_stmt_get_result($check_appt));;
mysqli_stmt_free_result($check_appt);
mysqli_stmt_close($check_appt);
header('Content-Type: application/json');
$resultRatio = $result['0']['0'];
$infoResult = array('avgRatio' => $resultRatio, 'quality' => ($resultRatio > .006 ? 'bad' : 'good'));
echo json_encode($infoResult);

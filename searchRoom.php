<?php
ini_set('display_errors', 1);
session_start();

$roomNumber = $_REQUEST["roomNumber"];

$email = $_SESSION['email'];

$mysqli = new mysqli("localhost", "root", "", "schema1");

$query = mysqli_query($mysqli, "select id from users where email='$email'");
$row = mysqli_fetch_assoc($query);
$userId = $row['id'];

date_default_timezone_set('America/Chicago'); // change this?
$today = date("Y-m-d H:i:s"); 
$query = mysqli_query($mysqli, "select ro.roomNumber, re.fromTime, re.toTime, re.cancelled from reservations re, rooms ro where re.fromTime > '$today' and re.cancelled = 0 and re.roomId = ro.id and re.userId = '$userId' and ro.roomNumber = '$roomNumber';");
$arr = array();
while($row = mysqli_fetch_assoc($query)) {
	$arr[] = array($row["roomNumber"], $row["fromTime"], $row["toTime"], $row["cancelled"]);
}

mysqli_close($mysqli);
echo json_encode($arr);
?>
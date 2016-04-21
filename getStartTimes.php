<?php
ini_set('display_errors', 1);
session_start();

$roomNumber = $_REQUEST["roomNumber"];
$date = $_REQUEST["date"];

$mysqli = new mysqli("localhost", "root", "", "schema1");

$query = mysqli_query($mysqli, "select id from rooms where roomNumber='$roomNumber'");
$row = mysqli_fetch_assoc($query);
$roomId = $row['id'];

$query = mysqli_query($mysqli, "select fromTime, toTime from reservations where roomId = '$roomId' and cancelled = 0 and (DATE(fromTime) = '$date' or DATE(toTime) = '$date');");

$arr = array();
while($row = mysqli_fetch_assoc($query)) {
	$arr[] = array($row["fromTime"], $row["toTime"]);
}

echo json_encode($arr);
//echo $roomNumber . " " . $date . " " . $roomId;
mysqli_close($mysqli);
?>
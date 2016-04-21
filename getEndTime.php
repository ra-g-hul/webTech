<?php
ini_set('display_errors', 1);
session_start();

$roomNumber = $_REQUEST["roomNumber"];
$fromTime = $_REQUEST["fromTime"];
$limit = $_REQUEST["limit"];

$mysqli = new mysqli("localhost", "root", "", "schema1");

$query = mysqli_query($mysqli, "select id from rooms where roomNumber='$roomNumber'");
$row = mysqli_fetch_assoc($query);
$roomId = $row['id'];

$query = mysqli_query($mysqli, "select MIN(fromTime) from reservations where roomId = '$roomId' and cancelled = 0 and fromTime > '$fromTime' and fromTime < '$limit';");

$row = mysqli_fetch_assoc($query);
echo $row["MIN(fromTime)"];

mysqli_close($mysqli);
?>
<?php
ini_set('display_errors', 1);
session_start();

$roomNumber = $_REQUEST["roomNumber"];
$fromTime = $_REQUEST["fromTime"];
$toTime = $_REQUEST["toTime"];

$email = $_SESSION['email'];

$mysqli = new mysqli("localhost", "root", "", "schema1");

$query = mysqli_query($mysqli, "select id from users where email='$email'");
$row = mysqli_fetch_assoc($query);
$userId = $row['id'];

$query = mysqli_query($mysqli, "select id from rooms where roomNumber='$roomNumber'");
$row = mysqli_fetch_assoc($query);
$roomId = $row['id'];

$query = mysqli_query($mysqli, "insert into reservations (userId, roomId, fromTime, toTime) values ('$userId', '$roomId', '$fromTime', '$toTime');");
if (!$query) {
    die('Invalid query: ' . mysqli_error($mysqli));
}

mysqli_close($mysqli);
echo $userId . " " . $roomId . " " . $fromTime . " " . $toTime;
?>
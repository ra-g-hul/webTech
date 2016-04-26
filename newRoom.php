<?php
ini_set('display_errors', 1);
session_start();

$roomNumber = $_REQUEST["roomNumber"];
$type = $_REQUEST["type"];
$repair = $_REQUEST["repair"];

$email = $_SESSION['email'];

$mysqli = new mysqli("localhost", "root", "", "schema1");

$query = mysqli_query($mysqli, "select * from rooms where roomNumber='$roomNumber'");
$rows = mysqli_num_rows($query);
if ($rows == 0) {
	$query = mysqli_query($mysqli, "insert into rooms (roomNumber, type, repair) values ('$roomNumber', '$type', '$repair');");
	if (!$query) {
		die('Invalid query: ' . mysqli_error($mysqli));
	}
	echo "done";
} else {
	$_SESSION['Error'] = "Error: Room number already exists.";
}
mysqli_close($mysqli);
?>
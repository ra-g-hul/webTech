<?php
ini_set('display_errors', 1);
session_start();

$roomNumber = $_REQUEST["roomNumber"];

$email = $_SESSION['email'];

$mysqli = new mysqli("localhost", "root", "", "schema1");

$query = mysqli_query($mysqli, "update rooms set repair = 0 where roomNumber = '$roomNumber';");
if (!$query) {
	die('Invalid query: ' . mysqli_error($mysqli));
}

echo $roomNumber;
mysqli_close($mysqli);
?>
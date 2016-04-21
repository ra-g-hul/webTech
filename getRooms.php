<?php
ini_set('display_errors', 1);
$type = $_REQUEST["type"];
$mysqli = new mysqli("localhost", "root", "", "schema1");
if($type != "All") {
	$query = mysqli_query($mysqli, "select roomNumber from rooms where repair = 0 and type = (select id from roomtype where type = '$type');");
	$rows = mysqli_num_rows($query);
	if ($rows > 0) {
		$arr = array();
		while($row = mysqli_fetch_assoc($query)) {
			array_push($arr, $row["roomNumber"]);
		}
		echo json_encode($arr);
	} else {
		$_SESSION['Error'] = "Error: No matches found.";
	}
} else {
	$query = mysqli_query($mysqli, "select roomNumber from rooms where repair = 0;");
	$rows = mysqli_num_rows($query);
	if ($rows > 0) {
		$arr = array();
		while($row = mysqli_fetch_assoc($query)) {
			array_push($arr, $row["roomNumber"]);
		}
		echo json_encode($arr);
	} else {
		$_SESSION['Error'] = "Error: No matches found.";
	}
}
mysqli_close($mysqli);
?>
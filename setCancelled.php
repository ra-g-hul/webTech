<?php
ini_set('display_errors', 1);
session_start();

$mysqli = new mysqli("localhost", "root", "", "schema1");
$ids = $_REQUEST["ids"];
$ids = implode(',', $ids);
$query = mysqli_query($mysqli, "update reservations set cancelled = 1 where id in ('$ids');");

echo "done";
mysqli_close($mysqli);
?>
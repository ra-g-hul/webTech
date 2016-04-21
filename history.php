<?php
ini_set('display_errors', 1);
session_start();

$mysqli = new mysqli("localhost", "root", "", "schema1");
$email = $_SESSION['email'];
$query = mysqli_query($mysqli, "select * from users where email='$email'");
$row = mysqli_fetch_assoc($query);
$name = $row['name'];
if(!isset($email)){
	mysqli_close($mysqli);
	header('Location: login.html'); 
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>History</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>
		$(document).ready(function(){

		});
	</script>
</head>
<body>
	Welcome, <?php echo $name; ?>!</br>
	See all reservations
	<table>
		<tr><th>Room Number</th><th>From</th><th>To</th><th>Cancelled</th></tr>
		<?php
			date_default_timezone_set('America/Chicago'); // change this?
			$today = date("Y-m-d H:i:s"); 
			$query = mysqli_query($mysqli, "select ro.roomNumber, re.fromTime, re.toTime, re.cancelled from reservations re, rooms ro where re.fromTime > '$today' and re.cancelled = 0 and re.roomId = ro.id;");
			while($row = mysqli_fetch_assoc($query)) {
				echo "<tr><td>" . $row["roomNumber"] . "</td><td>" . $row["fromTime"] . "</td><td>" . $row["toTime"] . "</td><td>" . $row["cancelled"] . "</td></tr>";
			}
		?>
	</table>
</body>
</html>
	

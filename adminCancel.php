<?php
ini_set('display_errors', 1);
session_start();

$mysqli = new mysqli("localhost", "root", "", "schema1");
$email = $_SESSION['email'];
$query = mysqli_query($mysqli, "select * from users where email='$email'");
$row = mysqli_fetch_assoc($query);
$name = $row['name'];

$query = mysqli_query($mysqli, "select id from users where email='$email'");
$row = mysqli_fetch_assoc($query);
$userId = $row['id'];

if(!isset($email)){
	mysqli_close($mysqli);
	header('Location: login.html'); 
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Cancel reservation</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#mainForm").submit(function(e) {
				var checkedValues = $('input:checkbox:checked').map(function() {
					return this.value;
				}).get();
				
				$.ajax({
					type: "POST",
					url: "setCancelled.php",
					data: {
						ids: checkedValues
					},
					success: function(result) {
						if(result){
							alert("Cancelled selected reservations.");
						} else {
							alert("Sorry, there was a problem. Please try again.");
						}
					}
				});
			});
		});
	</script>
</head>
<body>
	Welcome, <?php echo $name; ?>!</br>
	Cancel a reservation
	<form id = "mainForm">
		<table>
			<tr><th></th><th>Room Number</th><th>From</th><th>To</th></tr>
			<?php
				date_default_timezone_set('America/Chicago'); // change this?
				$today = date("Y-m-d H:i:s"); 
				$query = mysqli_query($mysqli, "select re.id, ro.roomNumber, re.fromTime, re.toTime from reservations re, rooms ro where re.fromTime > '$today' and re.cancelled = 0 and re.roomId = ro.id;");
				while($row = mysqli_fetch_assoc($query)) {
					echo "<tr><td><input type = 'checkbox' value = " . $row["id"] . "></td><td>" . $row["roomNumber"] . "</td><td>" . $row["fromTime"] . "</td><td>" . $row["toTime"] . "</td></tr>";
				}
			?>
		</table>
		<input type="submit" name = "submit" value = "Submit">
	</form>
</body>
</html>
	

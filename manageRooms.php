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
	<title>Manage rooms</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			
			$("#addRoom").submit(function (e) {
				//e.preventDefault();
				var empty = $(this).parent().find("input").filter(function() {
						return this.value === "";
					});
				if(!empty.length) {
					$.ajax({
						type: "POST",
						url: "newRoom.php",
						data: {
							roomNumber: $("#roomNumber").val(),
							type: $("#type option:selected").val(),
							repair: $("#repair option:selected").val()
						},
						success: function(result){
							if(result){
								alert("Added new room.");
							} else {
								alert("Sorry, there was a problem. Please try again.");
							}
						}
					});
				} else {
					alert("Please fill all fields.");
				}
			});
			
			$("#setRepair").submit(function (e) {
				$.ajax({
					type: "POST",
					url: "setRepair.php",
					data: {
						roomNumber: $("#roomNumberRepair option:selected").text()
					},
					success: function(result){
						alert("Updated room.");
					}
				});
			});
			
			$("#unsetRepair").submit(function (e) {
				$.ajax({
					type: "POST",
					url: "unsetRepair.php",
					data: {
						roomNumber: $("#roomNumberNoRepair option:selected").text()
					},
					success: function(result){
						alert("Updated room.");	
					}
				});
			});
			
		});
	</script>
</head>
<body>
	Welcome, <?php echo $name; ?>!</br>
	Manage rooms </br>
	Add a room </br>
	<form id = "addRoom">
		Room number <input type = "text" id="roomNumber"></br>
		Type 
		<select id = 'type'>
			<?php
				$query = mysqli_query($mysqli, "select * from roomtype");
				while($row = mysqli_fetch_assoc($query)) {
					echo "<option value = ".$row['id'].">".$row['type']."</option>"; 
				}
			?>
		</select><br/>
		Repair 
		<select id = 'repair'>
			<option value = "0">no</option>
			<option value = "1">yes</option>
		</select><br/>
		<input type="submit" name = "submit" value = "Submit">
	</form>
	Set repair
	<form id = "setRepair">
		<select id = 'roomNumberRepair'>
			<?php
				$query = mysqli_query($mysqli, "select roomNumber from rooms where repair = 0;");
				while($row = mysqli_fetch_assoc($query)) {
					echo "<option value = ".$row['roomNumber'].">".$row['roomNumber']."</option>"; 
				}
			?>
		</select><br/>
		<input type="submit" name = "submit" value = "Under repair">
	</form>
	Unset repair
	<form id = "unsetRepair">
		<select id = 'roomNumberNoRepair'>
			<?php
				$query = mysqli_query($mysqli, "select roomNumber from rooms where repair = 1;");
				while($row = mysqli_fetch_assoc($query)) {
					echo "<option value = ".$row['roomNumber'].">".$row['roomNumber']."</option>"; 
				}
			?>
		</select><br/>
		<input type="submit" name = "submit" value = "Not under repair">
	</form>
</body>
</html>
	

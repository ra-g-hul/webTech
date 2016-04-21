<?php
ini_set('display_errors', 1);
session_start();

$mysqli = new mysqli("localhost", "root", "", "schema1");
$email = $_SESSION['email'];
$query = mysqli_query($mysqli, "select * from users where email='$email'");
$row = mysqli_fetch_assoc($query);
$name = $row['name'];
$email = $row['email'];
if(!isset($email)){
	mysqli_close($mysqli);
	header('Location: login.html'); 
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome page</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>	
	
	<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.css" />
	
	<script type="text/javascript" src="js/jquery.timepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
	
	<script src="http://jonthornton.github.io/Datepair.js/dist/datepair.js"></script>
	<script src="http://jonthornton.github.io/Datepair.js/dist/jquery.datepair.js"></script>
	
	<script type="text/javascript" src="js/moment-with-locales.js"></script>
	<script>
		$(document).ready(function(){

			$('#date').datepicker({
				'format': 'm/d/yyyy',
				'autoclose': true,
				'startDate': new Date()			
			});
			$('#date').datepicker('update', new Date());
			
			$("#timepair .time").prop('disabled', true);
			
			$("#type").change(function() {
				$("#roomNumber").empty();
				$.ajax({
					type: "GET",
					url: "getRooms.php",
					data: "type=" + $("#type").val(),
					success: function(result){
						var rooms = JSON.parse(result);
						for(var i = 0; i < rooms.length; i++) {
							$("#roomNumber").append($("<option></option>").attr("value", rooms[i]).text(rooms[i])); 
						}
						$("#roomNumber").trigger("change")
					}
				});
			});
			
			$("#type").trigger("change");
			
			$(".updateTime").change(function() {
				var date = moment($('#date').datepicker("getDate")).format('YYYY-MM-DD');
				var filled = [];
				$.ajax({
					type: "GET",
					url: "getStartTimes.php",
					data: {
						roomNumber: $("#roomNumber option:selected").text(),
						date: date
					},
					success: function(result){
						var pairs = JSON.parse(result);
						var length = pairs.length;
						var start;
						var end;
						for (var i = 0; i < length; i++) {
							start = pairs[i][0];
							end = pairs[i][1];
							startDate = moment(start).format('YYYY-MM-DD');
							endDate = moment(end).format('YYYY-MM-DD');
							if( moment(startDate).isSame(date) && moment(endDate).isSame(date)) { // reservations starts and ends in the same day
								filled.push([moment(start).format('h:mma'), moment(end).format('h:mma')]);
							}
							else if(moment(startDate).isSame(date) && !moment(endDate).isSame(date)) { // reservation begins on the selected date, ends the next day
								filled.push([moment(start).format('h:mma'), "11:59pm"]);
							}
							else { // reservation ends on the selected date, starts the previous day
								filled.push(["12:00am", moment(start).format('h:mma')]);
							}
						}
						$("#timepair .start").prop('disabled', false);
						$('#timepair .start').replaceWith("<input type='text' class='time start' />"); // replace the old field with a new one
						$('#timepair .start').timepicker({
							'showDuration': true,
							'timeFormat': 'g:ia',
							'step': 15,
							'disableTimeRanges': filled,
							'disableTextInput': true,
							'disableTouchKeyboard': true
						});
						console.log(filled);
					}
				});
			});
			
			$("#timepair .start").change(function() {
				var today = new Date();
				var fromTime = $("#timepair .start").timepicker("getTime", today); // relative to today
				var fromDate = $('#date').datepicker("getDate");
				var fromDay = fromDate.getDate();
				var fromMonth = fromDate.getMonth();
				var fromYear = fromDate.getFullYear();
				fromTime.setFullYear(fromYear, fromMonth, fromDay);
				fromTime = moment(fromTime).format('YYYY-MM-DD HH:mm:ss');
				$.ajax({
					type: "GET",
					url: "getEndTime.php",
					data: {
						roomNumber: $("#roomNumber option:selected").text(),
						fromTime: fromTime,
						limit: moment(fromTime).add(24, 'hours').format('YYYY-MM-DD HH:mm:ss')
					},
					success: function(result){
						var upto = moment(result).add(1, 'm').format('h:mma');
						$("#timepair .end").prop('disabled', false);
						$('#timepair .end').replaceWith("<input type='text' class='time end' />"); // replace the old field with a new one
						$('#timepair .end').timepicker({ // set timepicker on the new field
							'showDuration': true,
							'timeFormat': 'g:ia',
							'step': 15,
							'minTime': moment(fromTime).format('h:mma'),
							'maxTime': upto,
							'disableTextInput': true,
							'disableTouchKeyboard': true
						});
						//$('#timepair').datepair();
					}
				});
			});

			$("#mainForm").submit(function(e) {
				//e.preventDefault();
				var anyFieldIsEmpty = $("#mainForm :input").filter(function() {
					return $.trim(this.value).length === 0;
				}).length > 0;

				if (anyFieldIsEmpty) {
					alert("Please fill in all the fields.");
				} else {
					var today = new Date();
					var fromTimeSeconds = $("#timepair .start").timepicker('getSecondsFromMidnight');
					var toTimeSeconds = $("#timepair .end").timepicker('getSecondsFromMidnight');
					
					if(fromTimeSeconds == toTimeSeconds) {
						alert("A reservation must be atleast 15 minutes long.");
					} else {
						var fromTime = $("#timepair .start").timepicker("getTime", today); // relative to today
						var fromDate = $('#date').datepicker("getDate");
						var fromDay = fromDate.getDate();
						var fromMonth = fromDate.getMonth();
						var fromYear = fromDate.getFullYear();
						fromTime.setFullYear(fromYear, fromMonth, fromDay);
						fromTime = moment(fromTime).format('YYYY-MM-DD HH:mm:ss');
						
						var toTime = $("#timepair .end").timepicker("getTime", today); // relative to today
						if (fromTimeSeconds > toTimeSeconds) {
							var nextDay = new Date();
							nextDay.setDate(fromDay+1);
							var toDayOfMonth = nextDay.getDate();
							var toMonth = nextDay.getMonth();
							var toYear = nextDay.getFullYear();
							toTime.setFullYear(toYear, toMonth, toDayOfMonth);
						} else {
							toTime.setFullYear(fromYear, fromMonth, fromDay);
						}
						toTime = moment(toTime).format('YYYY-MM-DD HH:mm:ss');
						
						$.ajax({
							type: "POST",
							url: "reserve.php",
							data: {
								roomNumber: $("#roomNumber option:selected").text(),
								fromTime: fromTime,
								toTime: toTime
							},
							success: function(result){
								if(result){
									alert("You have a reservaton from " + fromTime + " to " + toTime + ".");
								} else {
									alert("Sorry, there was a problem. Please try again.");
								}
							}
						});
					}
				}
			});
		});
		
	</script>
</head>
<body>
	Welcome, <?php echo $name; ?>!</br>
	Reserve a room
	<form id = "mainForm">
		Filter by type
		<select id = 'type'>
			<option value = 'All' selected='selected'>All</option>
			<?php
				$query = mysqli_query($mysqli, "select type from roomtype");
				while($row = mysqli_fetch_assoc($query)) {
					echo "<option value = ".$row['type'].">".$row['type']."</option>"; 
				}
				echo "";
			?>
		</select><br/>
		Room number
		<select id = 'roomNumber' class = "updateTime">
		</select><br/>
		Date <input type="text" id="date" class = "updateTime"><br/>
		<p id="timepair">
			From
			<input type="text" class="time start" /> to
			<input type="text" class="time end" />
		</p>
		<input type="submit" name = "submit" value = "Submit">
	</form>
	<a href = "cancelReservation.php">Cancel a reservation</a>
	<a href = "history.php">See all reservations</a>

</body>
</html>
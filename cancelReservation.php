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
	<title>Cancel reservation</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" media="screen" href="css/reset.css">
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
	<link rel="stylesheet" type="text/css" media="screen" href="css/project.css">
	
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
<header>
  <div class="main">
    <div class="wrap">
      <h1><a href="index.html"><img src="images/rt.jpg" alt="" height="75" width="200"></a></h1>
      <div class="slogan">Reservations made easy!</div>
      <div class="tooltips"> <a href="#"><img src="images/icon-1.png" alt=""></a><a href="#"><img src="images/icon-2.png" alt=""></a><a href="#"><img src="images/icon-3.png" alt=""></a>Welcome, <?php echo $name; ?>! </div>
	</div>
    <div class="nav-shadow">
      <div>
        <nav>
          <ul class="menu">
            <li><a href="reserve1.php">Reserve Room</a></li>
            <li class="current"><a href="cancelReservation.php">Cancel Reservation</a></li>
            <li><a href="history.php">View Reservations</a></li>
			<li><a href="#">Logout</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</header>

<section id="content">
  <div>
    <div class="wrap">
      <div class="col-1 border-2">
       <form id = "mainForm">
		<table>
			<tr><th></th><th>Room Number</th><th>From</th><th>To</th></tr>
			<?php
				date_default_timezone_set('America/Chicago'); // change this?
				$today = date("Y-m-d H:i:s"); 
				$test="select re.id, ro.roomNumber, re.fromTime, re.toTime from reservations re, rooms ro where re.fromTime > '$today' and re.cancelled = 0 and re.roomId = ro.id;";
				$query = mysqli_query($mysqli, "select re.id, ro.roomNumber, re.fromTime, re.toTime from reservations re, rooms ro where re.fromTime > '$today' and re.cancelled = 0 and re.roomId = ro.id;");
				while($row = mysqli_fetch_assoc($query)) {
					echo "<tr><td><input type = 'checkbox' value = " . $row["id"] . "></td><td>" . $row["roomNumber"] . "</td><td>" . $row["fromTime"] . "</td><td>" . $row["toTime"] . "</td></tr>";
				}
			?>
		</table>
		<input type="submit" name = "submit" value = "Submit">
	</form>

 </div>
  </div>
</section>

	</body>
</html>
	

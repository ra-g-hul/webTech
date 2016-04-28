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
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" media="screen" href="css/reset.css">
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
	<script src="js/jquery-1.7.min.js"></script>
	<script src="js/jquery.easing.1.3.js"></script>
	<script src="js/FF-cash.js"></script>
	<script>
		$(document).ready(function(){

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
            <li><a href="cancelReservation.php">Cancel Reservation</a></li>
            <li class="current"><a href="history.php">View Reservations</a></li>
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
	</div>
	</div>
	</section>
</body>
</html>
	

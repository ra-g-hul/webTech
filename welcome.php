<?php
ini_set('display_errors', 1);
$mysqli = new mysqli("localhost", "root", "", "schema1");
session_start();
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
</head>
<body>
	Name: <?php echo $name; ?></br>
	Email: <?php echo $email; ?></br>
</body>
</html>
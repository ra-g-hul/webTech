<?php
ini_set('display_errors', 1);
session_start(); 
if (isset($_POST['submit'])) {
	if (empty($_POST['emailSignup']) || empty($_POST['pwdSignup1']) || empty($_POST['pwdSignup2']) || empty($_POST['name'])) {
		$_SESSION['Error'] = "Error: Empty field(s).";
	}
	else {
		$email= $_POST['emailSignup'];
		$email = stripslashes($email);
		$mysqli = new mysqli("localhost", "root", "", "schema1");
		$query = mysqli_query($mysqli, "select * from users where email='$email'");
		$rows = mysqli_num_rows($query);
		if ($rows == 0) {
			$pwd = $_POST['pwdSignup1'];
			$name = $_POST['name'];
			
			// hashing
			$cost = 10;
			$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
			$salt = sprintf("$2a$%02d$", $cost) . $salt;
			$hash = crypt($pwd, $salt);
			
			$query = "insert into users (`email`, `password`, `name`) values ('$email','$hash','$name')";
			if (mysqli_query($mysqli, $query)) {
				$_SESSION['email'] = $email;
				header("location: welcome.php");
			} else {
				$_SESSION['Error'] = "Error: " . mysqli_error($mysqli);
			}
		} else {
			$_SESSION['Error'] = "Error: Email already exists.";
		}
		mysqli_close($mysqli); // Closing Connection
	}
}
?>
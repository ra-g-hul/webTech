<?php
ini_set('display_errors', 1);
session_start(); 
if (isset($_POST['submit'])) {
	if (empty($_POST['emailLogin']) || empty($_POST['pwdLogin'])) {
		$_SESSION['Error'] = "Error: Empty field(s).";
	}
	else {
		$email= $_POST['emailLogin'];
		$email = stripslashes($email);
		$pwd = $_POST['pwdLogin'];
		$mysqli = new mysqli("localhost", "root", "", "schema1");
		$query = mysqli_query($mysqli, "select * from users where email='$email'");
		$rows = mysqli_num_rows($query);
		if ($rows == 1) {
			$row = mysqli_fetch_assoc($query);
			
			$hash = $row['password'];
			if ( hash_equals($hash, crypt($pwd, $hash)) ) {
				$_SESSION['email'] = $email;
				header("location: welcome.php");
			}
/*
			if($pwd == $pwdReal) {
				$_SESSION['email'] = $email;
				header("location: welcome.php");
			} 
*/		
			else {
				$_SESSION['Error'] = "Error: Incorrect password.";
			}
		} else {
			$_SESSION['Error'] = "Error: Email already exists.";
		}
		mysqli_close($mysqli); // Closing Connection
	}
}
?>
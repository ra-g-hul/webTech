
<?php

$mysqli = new mysqli("localhost", "root", "", "SCHEMA1");

if(isset($_POST['name']))
{
	$name=$_POST['name'];

	$checkdata=" SELECT name FROM users WHERE name='$name' ";

	$query=mysqli_query($mysqli, $checkdata);

	if(mysqli_num_rows($query)>0)
	{
	echo "<img src='images/cross.jpg' alt='Already Exists' title='Already Exists' height='30' width='30'>";
	}
	else
	{
	echo "<img src='images/tick.jpg' alt='Ok' title='Ok' height='30' width='30'>";
	}
exit();
}

if(isset($_POST['email']))
{
	$emailId=$_POST['email'];

	$checkdata=" SELECT email FROM users WHERE email='$emailId' ";

	$query=mysqli_query($mysqli, $checkdata);

	if(mysqli_num_rows($query)>0)
	{
	echo "<img src='images/cross.jpg' alt='Already Exists' title='Already Exists' height='30' width='30'>";
	}
	else
	{
	echo "<img src='images/tick.jpg' alt='Ok' title='Ok' height='30' width='30'>";
	}
exit();
}
?>
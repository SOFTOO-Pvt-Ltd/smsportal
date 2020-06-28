<?php
define('SERVER', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DATABASE', 'sms');

$con=mysqli_connect(SERVER, USERNAME, PASSWORD,DATABASE);
	if (!$con) {
	die("Connection failed: " . mysqli_connect_error());
	}
?>
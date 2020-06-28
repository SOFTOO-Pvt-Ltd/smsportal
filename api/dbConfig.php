<?php

define('SERVER', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', 'Yahya8523!');
define('DATABASE', 'staging_sms');
define('msisdn', '923458590061');
define('passwordms', '5000');


$msisdn=msisdn;
$password=passwordms;

class DB {
    function connect()
	{
        $con = mysqli_connect(SERVER, USERNAME, PASSWORD,DATABASE) or die('Connection error -> ' . mysql_error());
		mysqli_set_charset($con,"utf8");
		return $con;
    }
}


$con=mysqli_connect(SERVER, USERNAME, PASSWORD,DATABASE);
	if (!$con) {
	   die("Connection failed: " . mysqli_connect_error());
	}
	
	
	date_default_timezone_set("Asia/Karachi");
	/*define('TIMEZONE', '"Asia/Riyadh');
    date_default_timezone_set(TIMEZONE);*/
	
			$now = new DateTime();
			
			$mins = $now->getOffset() / 60;
			$sgn = ($mins < 0 ? -1 : 1);
			$mins = abs($mins);
			$hrs = floor($mins / 60);
			$mins -= $hrs * 60;
			
		$offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
	
	$query=("SET @@session.time_zone='".$offset."';");
	mysqli_query($con,$query) ;
	mysqli_set_charset($con,"utf8");
?>

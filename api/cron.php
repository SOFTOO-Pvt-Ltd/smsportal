<?php

// Handle cron job for scheduled broadcast messages

$filetype="dbMethods";
include_once 'helpingMethods.php';
include_once 'appMethods.php';
global $con;


$date = new DateTime('now', new DateTimeZone('Asia/Karachi'));
$currentTime = $date->format('d-m-Y H:i:s');
$currentTime = strtotime($currentTime);

$query_sch = "SELECT sc.id, sc.time_interval, sc.time_last_fired, sd.commaseparatedn, sd.group_id, sd.message, sd.method, sd.url   FROM `scheduler` as sc INNER JOIN scheduler_data as sd ON sc.scheduler_data_id = sd.id WHERE sc.time_last_fired IS NULL AND sc.time_interval <= ". $currentTime;

$schedules = mysqli_query($con,$query_sch);
if (mysqli_error($con) != '')
{
	return  "mysql_Error:-".mysqli_error($con);
	exit();
}

if (mysqli_num_rows($schedules) > 0)
{
	while($row = mysqli_fetch_assoc($schedules)) {
		$params = array();
		try
		{
			if ($row['group_id'] != '')
			{
				$params['group_id'] = $row['group_id'];
				$params['commaseparatedn'] = $row['commaseparatedn'];
				$params['text'] = $row['message'];
				$params['method'] = 'broadcast';
				$params['url'] = 'broadcast';
				$params['operator_id'] = '';
				$addAdmin = AppMethods::broadcast($params);
				$response = json_decode($addAdmin);
				if ($response->status == 201)
				{
					$query_1 = "UPDATE  `scheduler` SET `time_last_fired`='1'  WHERE `id`='{$row['id']}' ";
					$result = mysqli_query($con,$query_1) ;
				}
			} else if ($row['commaseparatedn'] != '')
			{
				$params['group_id'] = '';
				$params['commaseparatedn'] = $row['commaseparatedn'];
				$params['text'] = $row['message'];
				$params['method'] = 'broadcast';
				$params['url'] = 'broadcast';
				$params['operator_id'] = '';
				$addAdmin = AppMethods::broadcast($params);
				$response = json_decode($addAdmin);
				if ($response->status == 201)
				{
					$query_1 = "UPDATE  `scheduler` SET `time_last_fired`='1'  WHERE `id`='{$row['id']}' ";
					$result = mysqli_query($con,$query_1) ;
				}
			}

		} catch (Exception $e)
		{
			
		}
	}
}
print 'Messages Sent';
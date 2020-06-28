<?php
include_once 'dbMethods.php';

class AppMethods 
{

/* 1:------------------------------method start here getSms 1------------------------------*/
static function getSms($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::getSms($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			  
				$response["status"] = 201;
				$response["message"] = "created!";
				$response["data"] = $result;
				//$response["data"] = '';
				header("HTTP/1.1 201 Created");
			
		}
			return json_encode($response);
}



/* 1:-----------------------------method start here login 1------------------------------*/
static function login($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::login($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			if ($result == 'not_valid_credential') 
				{
					$response["status"] = 400 ;
					$response["message"] = 'User ID or password is incorrect.';
				}
				else 
				{
			  
					$response["status"] = 200;
					$response["message"] = "created!";
					//$response["data"] = $result;
					$response["data"] = '';
					header("HTTP/1.1 200 OK");
				}
			
		}
			return json_encode($response);
}



/* 1:------------------------------method start here logout 1------------------------------*/
static function logout($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::logout($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			
			  
					$response["status"] = 200;
					$response["message"] = "created!";
					//$response["data"] = $result;
					$response["data"] = 'logout';
					header("HTTP/1.1 200 OK");
			
			
		}
			return json_encode($response);
}



/* 1:------------------------------method start here sms 1------------------------------*/
static function sms($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::sms($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			  
				$response["status"] = 200;
				$response["message"] = "List!";
				$response["data"] = $result;
				//$response["data"] = '';
				header("HTTP/1.1 200 OK");
			
		}
			return json_encode($response);
}




/* 1:------------------------------method start here pending 1------------------------------*/
static function pending($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::pending($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			  
				$response["status"] = 200;
				$response["message"] = "List!";
				$response["data"] = $result;
				//$response["data"] = '';
				header("HTTP/1.1 200 OK");
			
		}
			return json_encode($response);
}



/* 1:------------------------------method start here pending 1------------------------------*/
static function pending2($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::pending2($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			  
				$response["status"] = 200;
				$response["message"] = "List!";
				$response["data"] = $result;
				//$response["data"] = '';
				header("HTTP/1.1 200 OK");
			
		}
			return json_encode($response);
}


/* 1:------------------------------method start here broadcast 1------------------------------*/
static function broadcast($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::broadcast($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			  
				$response["status"] = 201;
				$response["message"] = "created!";
				//$response["data"] = $result;
				$response["data"] = '';
				header("HTTP/1.1 201 Created");
			
		}
			return json_encode($response);
}


/* ******* Method start to send later broadcast ****************/

static function broadcastltr($params)
{
	$response = array("status" => NULL, "message" => NULL, "data" => NULL);
	$result = DbMethods::broadcastltr($params);
	$error = strpos($result, 'mysql_Error:-');
	if($error ===0)
	{
		$response["status"] = 500;
		$response["message"] = "Your device has lost connection due to database error.";
		$response["data"] = $result;
		header("HTTP/1.1 500 Internal Server Error");
	}
	else
	{
		  
			$response["status"] = 201;
			$response["message"] = "created!";
			//$response["data"] = $result;
			$response["data"] = '';
			header("HTTP/1.1 201 Created");
		
	}
		return json_encode($response);
}

/* ******* Method end to send later broadcast ****************/

/* 1:------------------------------method start here groupdelete 1------------------------------*/
static function groupdelete($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::groupdelete($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			
			  
					$response["status"] = 200;
					$response["message"] = "deleted!";
					//$response["data"] = $result;
					
					header("HTTP/1.1 200 OK");
			
			
		}
			return json_encode($response);
}



/* 1:------------------------------method start here groupdelete 1------------------------------*/
static function ConfirmagentDelete($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::ConfirmagentDelete($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			
			  
					$response["status"] = 200;
					$response["message"] = "deleted!";
					//$response["data"] = $result;
					
					header("HTTP/1.1 200 OK");
			
			
		}
			return json_encode($response);
}




/* 1:------------------------------method start here replay 1------------------------------*/
static function replay($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::replay($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			  
				$response["status"] = 201;
				$response["message"] = "created!";
				//$response["data"] = $result;
				$response["data"] = '';
				header("HTTP/1.1 201 Created");
			
		}
			return json_encode($response);
}




/* 1:------------------------------method start here groupdelete 1------------------------------*/
static function autoreply($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::autoreply($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			
			  
					$response["status"] = 200;
					$response["message"] = "sent!";
					$response["data"] = $result;
					
					header("HTTP/1.1 200 OK");
			
			
		}
			return json_encode($response);
}




/* 1:------------------------------method start here groupdelete 1------------------------------*/
static function recipients($params)
 {
		$response = array("status" => NULL, "message" => NULL, "data" => NULL);
		$result = DbMethods::recipients($params);
		$error = strpos($result, 'mysql_Error:-');
		if($error ===0)
		{
			$response["status"] = 500;
			$response["message"] = "Your device has lost connection due to database error.";
			$response["data"] = $result;
			header("HTTP/1.1 500 Internal Server Error");
		}
		else
		{
			
			  
					$response["status"] = 200;
					$response["message"] = "deleted!";
					$response["data"] = $result;
					
					header("HTTP/1.1 200 OK");
			
			
		}
			return json_encode($response);
}


									
/* END-----------------------------END END END END-----------------------------END-*/
}






?>
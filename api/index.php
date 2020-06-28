<?php
//sytem error
header('content-type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With,AccessToken');


error_reporting(1);
include_once 'appMethods.php';
include_once 'outh.php';
Page::Load();

class Page {
	
  static function Load() 
  {
	$pattern = '/[^0-9]/'; 	
	$patternemail = "/([\w\-]+\@[\w\-]+\.[\w\-]+)/";
	$regdecimal ='/^[0-9]+(\.[0-9]{1,2})?$/'; 
	$regdouble ='/^[0-9]+(\.[0-9]{1,20})?$/'; 
	
	$bothmethods=outh::methodParamscheck();
	extract($bothmethods);
	
	  switch (array($methodName, $method)) 
	  {




/* 1:------------------------------method start here login------------------------------*/
		
		case array("login", "POST"):
		  {

			if( $params['username'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: username', "data" => NULL);
					echo json_encode($response);
					exit();
				}
			if( $params['password'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: to', "data" => NULL);
					echo json_encode($response);
					exit();
				}		
			
			
			echo $addAdmin = AppMethods::login($params);
			break;	
		}
		

/* 1:------------------------------method start here logout------------------------------*/
		
		case array("logout", "POST"):
		  {

			
			echo $addAdmin = AppMethods::logout($params);
			break;	
		}
		

/* 1:------------------------------method start here login------------------------------*/
		
		case array("broadcast", "POST"):
		  {
				
				if( $params['text'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: text', "data" => NULL);
					echo json_encode($response);
					exit();
				}
			echo $addAdmin = AppMethods::broadcast($params);
			break;	
		}

		case array("broadcastltr", "POST"):
		  {
				if( $params['text'] == '' || $params['datetime'] == '')
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: text', "data" => NULL);
					echo json_encode($response);
					exit();
				}
			echo $addAdmin = AppMethods::broadcastltr($params);
			break;	
		}



/* 1:------------------------------method start here login------------------------------*/
		
		case array("groupdelete", "POST"):
		  {
			if( $params['id'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: id', "data" => NULL);
					echo json_encode($response);
					exit();
				}		
			
			
			echo $addAdmin = AppMethods::groupdelete($params);
			break;	
		}



/* 1:------------------------------method start here login------------------------------*/
		
		case array("ConfirmagentDelete", "POST"):
		  {
			if( $params['id'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: id', "data" => NULL);
					echo json_encode($response);
					exit();
				}		
			
			
			echo $addAdmin = AppMethods::ConfirmagentDelete($params);
			break;	
		}


/* 1:------------------------------method start here login------------------------------*/
		
		case array("replay", "POST"):
		  {
			if( $params['parent_id'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: parent_id', "data" => NULL);
					echo json_encode($response);
					exit();
				}		
			
			
				
			if( $params['agentid'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: agentid', "data" => NULL);
					echo json_encode($response);
					exit();
				}
				
				if( $params['text'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: text', "data" => NULL);
					echo json_encode($response);
					exit();
				}
				
				
				if( $params['from'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: from', "data" => NULL);
					echo json_encode($response);
					exit();
				}
				
			
			
			if( $params['operator'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: operator', "data" => NULL);
					echo json_encode($response);
					exit();
				}
					
				
			echo $addAdmin = AppMethods::replay($params);
			break;	
		}




/* 1:------------------------------method start here ------------------------------*/
        case array("getSms", "GET"):
         {
			   if( $params['from'] == '' )
				{
					$response = array("status" => 422, "message" => 'Invalid Parameter: from', "data" => NULL);
					header("HTTP/1.1 422 Unprocessable Entity");
					echo json_encode($response);
					exit();
				}
				
				if( $params['text'] == '' )
				{
					$response = array("status" => 422, "message" => 'Invalid Parameter: text', "data" => NULL);
					header("HTTP/1.1 422 Unprocessable Entity");
					echo json_encode($response);
					exit();
				}
				
				
				if($params['from'] != '' )
				{
					
					 if (!preg_match("/^((\92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{12}$|^\d{4}-\d{7}$/",$params['from']))
						{
							$response = array("status" => 422, "message" => 'Pakistani Mobile formate is required Like 923465346311: from', "data" => NULL);
							header("HTTP/1.1 422 Unprocessable Entity");
							echo json_encode($response);
							exit();
						}
						
						
				}
				
				
				
				
				/*
				if( $params['to'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: to', "data" => NULL);
					echo json_encode($response);
					exit();
				}*/
				
				
				
			
				
			
			/*	
			if( $params['to'] != '' )
				{
					
					 if (!preg_match("/^((\92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/",$params['to']))
						{
							$response = array("status" => 406, "message" => 'Pakistani Mobile formate is required Like 923465346311: to', "data" => NULL);
							echo json_encode($response);
							exit();
						}
				}	*/		
			
				
			/*if( $params['operator'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: operator', "data" => NULL);
					echo json_encode($response);
					exit();
				}	*/
			 	$msg = serialize($params);
			 	$unserialzedArray = (unserialize($msg));
				$newText = '';
				$textFound = FALSE;
				foreach ($unserialzedArray as $key => $value) {
				    if ($key == 'method' || $key == 'url' || $key == 'from') {
				        continue;
				    } else if ($key == 'text') {
				        $newText = $value;
				        $textFound = TRUE;
				    } else {
				        if ($textFound) {
				            $newText = $newText . ' & '. str_replace('_', ' ', $key);
				        } 
				    }
				}
				$params['text'] = $newText;
				echo $adminGetProfile = AppMethods::getSms($params);
			break;
		}
		



/* 1:------------------------------method start here ------------------------------*/
        case array("autoreply", "GET"):
         {
			 if( $params['from'] == '' )
				{
					$response = array("status" => 406, "message" => 'Invalid Parameter: from', "data" => NULL);
					echo json_encode($response);
					exit();
				}
				
				
		
				echo $adminGetProfile = AppMethods::autoreply($params);
			break;
		}
		
/* 1:------------------------------method start here login------------------------------*/		
					
	
	 case array("sms", "GET"):
         {
			 
			 if( $params['index'] != '' )
				{
					$index = preg_match($pattern, $params['index']);
					if (($index > 0 || $params['index'] ==0) ) 
					{
						$response = array("status" => 406, "message" => 'Invalid Parameters index', "data" => NULL);
						echo json_encode($response);
						exit();
					} 
					
				}
				else
				{
					$params['index']=1;
				}
			
			 
				echo $adminGetProfile = AppMethods::sms($params);
			break;
		}
			

/* 1:------------------------------method start here login------------------------------*/


 case array("pending", "GET"):
         {
			 
			 if( $params['id'] < 0 )
				{
					
					$response = array("status" => 406, "message" => 'Invalid Parameters id', "data" => NULL);
					echo json_encode($response);
					exit();
					
				}
				echo $adminGetProfile = AppMethods::pending($params);
			break;
		}

/* 1:------------------------------method start here login------------------------------*/


 case array("pending2", "GET"):
         {
			 
			 if( $params['id'] == '' )
				{
					
					$response = array("status" => 406, "message" => 'Invalid Parameters id', "data" => NULL);
					echo json_encode($response);
					exit();
					
				}
				echo $adminGetProfile = AppMethods::pending2($params);
			break;
		}
		
/* 1:------------------------------method start here login------------------------------*/		

case array("recipients", "GET"):
         {
			 
			 if( $params['id'] == '' )
				{
					
					$response = array("status" => 406, "message" => 'Invalid Parameters id', "data" => NULL);
					echo json_encode($response);
					exit();
					
					
				}
				
			
			 
				echo $adminGetProfile = AppMethods::recipients($params);
			break;
		}
						

/* 1:------------------------------default default default default default------------------------------*/
		   default : 
		   {
			   $response = array("status" => 406, "message" => 'Invalid Method Name '.$methodName." Or Routes ".$_SERVER['REQUEST_METHOD'], "data" => NULL);
			   echo json_encode($response);
			   exit();
		   }
	  }
  }
																																																																																																																													
}		
		?>
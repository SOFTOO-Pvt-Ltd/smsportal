<?php
class outh 
{
 /* :------------------------------methodParamscheck check  ------------------------------*/
  static function methodParamscheck()
	 {
		$json=array();
		$params=array();
		$methodName = '';
		$method=  $_SERVER['REQUEST_METHOD'];
		$methodName= $_GET['method'];
		
		$postdata = @file_get_contents("php://input");
		if($postdata)
		 {    
			$json = json_decode($postdata, true);
			 if(!empty($json)){
					if($json ){
						foreach($json as $item => $value){
							$params[$item] = (!empty($value)) ? addslashes($value) : '';
						}
					}
				}
		 }
		 if(!empty($_POST))
		 {
		 	
			 foreach($_POST as $item => $value)
			 {
				$params[$item] = (!empty($value)) ? (is_array($value) ? implode($value, ',') : addslashes($value)) : '';
			 }
		 }
		 
		 if(!empty($_REQUEST))
		 {
			 foreach($_REQUEST as $item => $value)
			 {
				$params[$item] = (!empty($value)) ? (is_array($value) ? implode($value, ',') : addslashes($value)) : '';
			 }
		 }
		  
		 
		  if($methodName =='')
		  {
				$response = array("status" => 406, "message" => 'Wellcome to SMS Portal', "data" => NULL);
				echo json_encode($response);
				exit();
		  }
		  else
		  {
			  
			  return array("methodName"=>$methodName, "method"=> $method, "params"=> $params);
		  }
		 
	 }
	 
 /* :------------------------------outhentication check  ------------------------------*/	 
	static function outhforAll()
	 {
		  $headers = apache_request_headers();
		  if(isset($headers['AccessToken'] , $headers['id'] ))
		  {
			  if(!file_exists(session_save_path()."/sess_".$headers['AccessToken']) )
			  {
				  $error="1";
			  }
			  else
			  {
				  session_id($headers['AccessToken']);
				  session_start();
				  if($_SESSION['AccessToken'] != $headers['AccessToken'])
				  {
					  $error="1";
				  }
				  else if(sha1('story_factory'.$headers['id']) != $headers['AccessToken'])
				  {
					  $error="1";
				  }
			  }
		  }
		  else
		  {
			  $error="1";
		  }
		  if($error !="")
		  {
				$response = array("status" => 409, "message" => 'Invalid Parameter: Headers', "data" => NULL);
				echo json_encode($response);
				exit();
		  }
		  else
		  {
			  return $headers['id'];
		  }
		  
	  
	 }
}

?>
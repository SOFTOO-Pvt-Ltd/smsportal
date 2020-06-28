<?php
include_once 'dbConfig.php';
if($_POST)
{
	extract($_POST);
	$params['text']= $_POST['text'];
	$params['phone']=$_POST['phone'];
	$params['operator_id']=$_POST['operator_id'];
	if($params)
	HelpingMethods::send_sms($params);
}

class HelpingMethods {
/* 1:------------------------------method start here addUser 1------------------------------*/
	 static function get_sms_session_id() 
	   {
		  
		global $con;
		 $query = "SELECT  `session_id` 
		FROM `sms_session` WHERE `session_id` !='' AND TIMESTAMPDIFF(MINUTE,`datetime`,NOW()) < 26 ";
		$result = mysqli_query($con,$query) ;
		if (mysqli_error($con) != '')
		{
			return  "mysql_Error:-".mysqli_error($con);
			exit();
		}
		if (mysqli_num_rows($result) > 0)
			{
				$row = mysqli_fetch_assoc($result);
				return  $session_id=  $row['session_id'];
				
			}
			
			else
			{
			global $msisdn;
			global $password;	
		    $url = 'https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp?msisdn='.$msisdn.'&password='.$password.'';
			try{
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
					//  curl_setopt($ch, CURLOPT_POSTFIELDS,  urlencode($xmlRequest));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_VERBOSE, 0);
					$data = curl_exec($ch);
					curl_close($ch);
					
				
					//convert the XML result into array
					if(!$data)
					{
						//HelpingMethods::get_sms_session_id();
					}
					else
					{
						$data = json_decode(json_encode(simplexml_load_string($data)), true); 
						$response=$data['response'];
					    $session_id=$data['data'];
						
						
					    $query_d = "DELETE FROM `sms_session` ";
						mysqli_query($con,$query_d) ;
						
						 $query_s = "INSERT INTO `sms_session` SET  
						`session_id`='{$session_id}'" ;
						$result_no = mysqli_query($con,$query_s) ;
						if (mysqli_error($con) != '')
						{
						  return  "mysql_Error:-".mysqli_error($con);
						  exit();
						}
						
						return $session_id;
						   
					}
				}catch(Exception  $e)
				{
						$error=$e->getMessage();
			    }
			}
	     }
/* 1:------------------------------method start here addUser 1------------------------------*/		 
	 static function send_sms($params) 
	   {
		global $con;  
	
		   
if($params)	
{	
   
  $url='http://180.92.157.107:14809/mobimatic/sendbroadcast.php?username=softoo&password=softoo@123&from_id=9143&message='.urlencode($params['text']).'&msisdn='.$params['phone'];
 
			try{
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
					//  curl_setopt($ch, CURLOPT_POSTFIELDS,  urlencode($xmlRequest));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_VERBOSE, 0);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					$data = curl_exec($ch);
					curl_close($ch);
					
					if($data)
					{
						return 'send';
						unset($params);
						$params="";
						exit();
					}
					
						   
				
				}catch(Exception  $e)
				{
					
						$error=$e->getMessage();
			    }
		   
	       }
	   
	   }
	   
	   
	   
	   
	   
	   
	   /* 1:------------------------------method start here addUser 1------------------------------*/		 
	 static function send_sms_backupp($params) 
	   {
		global $con;  
	if(!isset($params['operator_id']))
	$params['operator_id']='';
		   
if($params)	
{	
   
	  $params['session_id']=  HelpingMethods::get_sms_session_id();
	  
 
	//$params['text']=urlencode($params['text']);	 
  $url = 'https://telenorcsms.com.pk:27677/corporate_sms2/api/sendsms.jsp?session_id='.$params['session_id'].'&to='.$params['phone'].'&text='.urlencode($params['text']).''.$params['operator_id'].'&mask=90131&unicode=true';
 
			try{
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
					//  curl_setopt($ch, CURLOPT_POSTFIELDS,  urlencode($xmlRequest));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_VERBOSE, 0);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					$data = curl_exec($ch);
					curl_close($ch);
					//print_r($data);
					if(!$data)
					{
						HelpingMethods::send_sms($params);
					}
					
					else
					{
						
						$data = json_decode(json_encode(simplexml_load_string($data)), true); 
						$response=$data['response'];
						if($data['data']=="Error 102")
						{
							$query_d = "DELETE FROM `sms_session` ";
						    mysqli_query($con,$query_d) ;
							 HelpingMethods::send_sms($params);
						}
					    
						
						/*global $con;
						
						$query_s = "INSERT INTO `test` SET  
						`type`='1',
						`text`='{$url}',
						`from`='{$params['phone']}'" ;
						$result_no = mysqli_query($con,$query_s) ;
						if (mysqli_error($con) != '')
						{
						  return  "mysql_Error:-".mysqli_error($con);
						   exit();
						}*/
						return 'send';
						unset($params);
						$params="";
						exit();
					}
						   
				
				}catch(Exception  $e)
				{
					
						$error=$e->getMessage();
			    }
		   
	       }
	   
	   }

}






?>

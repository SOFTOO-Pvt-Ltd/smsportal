<?php
include_once 'dbConfig.php';
class HelpingMethods {




/* 1:------------------------------method start here addUser 1------------------------------*/
	 static function ping_session_id($params) 
	   {
		  
		  $url = "https://telenorcsms.com.pk:27677/corporate_sms2/api/ping.jsp?session_id=".$params['session_id'];
			try{
					
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_ENCODING , "gzip"); 
					curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
					//  curl_setopt($ch, CURLOPT_POSTFIELDS,  urlencode($xmlRequest));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_VERBOSE, 0);
					
					
                    $data  = curl_exec($ch);
					
					if($data)
					{
						$data = json_decode(json_encode(simplexml_load_string($data)), true); 
						$response=$data['response'];
						$data=$data['data'];
						
						return $response;
						   
					}
					
					
				}catch(Exception  $e)
				{
				   $error=$e->getMessage();
			    }
	     }
		 
		 
	/* 1:------------------------------method start here addUser 1------------------------------*/
	 static function get_sms_session_id() 
	   {
		  
		   $url = 'https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp?msisdn=923458590061&password=5000';
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
						$data=$data['data'];
						
						if($response !='OK')
						{
						   HelpingMethods::get_sms_session_id();
						}
						else
						{
							return $data;
						}
						   
					}
				}catch(Exception  $e)
				{
						$error=$e->getMessage();
			    }
	     }
	 
		 
		 
/* 1:------------------------------method start here addUser 1------------------------------*/		 
	 static function send_sms($params) 
	   {
		  
		   if(!isset($params['operator_id']))
		   $params['operator_id']='';
		   
if($params)	
{	
   if($params['session_id']=='')
   {
	  $params['session_id']=  HelpingMethods::get_sms_session_id();
   }
	//$params['text']=urlencode($params['text']);	 
 echo  $url = 'https://telenorcsms.com.pk:27677/corporate_sms2/api/sendsms.jsp?session_id='.$params['session_id'].'&to='.$params['phone'].'&text='.urlencode($params['text']).''.$params['operator_id'].'&mask=90131&unicode=true';
 
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
						global $con;
						$session_id=$params['sms_id'];
						
						$query_s = "INSERT INTO `test` SET  
						`type`='{$session_id}',
						`text`='{$params['text']}',
						`from`='{$params['phone']}'" ;
						$result_no = mysqli_query($con,$query_s) ;
						if (mysqli_error($con) != '')
						{
						  return  "mysql_Error:-".mysqli_error($con);
						   exit();
						}
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
<?php
session_start();
$filetype="dbMethods";
include_once 'helpingMethods.php';
 
//include_once 'helpingMethods.php';
//include_once 'pushNotification.php';
class DbMethods {
   



/* 1:------------------------------method start here getSms ------------------------------*/
static function getSms($params) 
{
        global $con;
		$parent_id='';
		$agent_id='';
		$replay='';
        $query_1 = "SELECT
		 *,
		(SELECT `text` FROM `replay` where `enable`='0') as reply
		FROM  `sms` 
		where `from`='{$params['from']}'";
		$result = mysqli_query($con,$query_1) ;
		if (mysqli_error($con) != '')
		{
			return  "mysql_Error:-".mysqli_error($con);
			exit();
		}
		if (mysqli_num_rows($result) > 0) 
		{	
			$row = mysqli_fetch_assoc($result);
			$parent_id=$row['id'];	
			$agent_id=$row['agent_id'];
			$agent_id=$row['agent_id'];
			$params['operator_id']=$row['operator'];	
		    $params['reply']=$row['reply'];	
		} 	
		
		/*if($agent_id !='')
		{
			$parent_id ="`parent_id`='{$parent_id}',";
			$agent_id ="`agent_id`='{$agent_id}',";
		}*/
	
	
	
		 $query_n1 = "INSERT INTO `sms` SET  
		`from`='{$params['from']}',
		`text`='{$params['text']}',
		`operator`='{$params['operator']}'" ;
		
		$result_n1 = mysqli_query($con,$query_n1) ;
	    if (mysqli_error($con) != '')
		{
			return  "mysql_Error:-".mysqli_error($con);
			exit();
		}
		
	$id=mysqli_insert_id($con);
	$params['sms_id']=$id;
	if($params['reply'] !='')
	DbMethods::autoreply($params);
	
	return array("id"=>(int)$id) ;
}





/* 1:------------------------------method start here autoreply ------------------------------*/
static function autoreply($params) 
{
	   
	  
		$params['text']= $params['reply'];
		$params['phone']=$params['from'];
		if (preg_match("/^((\92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{12}$|^\d{4}-\d{7}$/",$params['phone']))
		{
			if($params['operator_id'] !='')
			{
			   $params['operator_id']="&operator_id=".$params['operator_id']."";
			}
			else
			{
			  $params['operator_id']="";
			}
			
				
				$path = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
				$file = substr( strrchr( $path, "/" ), 1) ; 
				$dir = str_replace( $file, '', $path ) ;
				$dir =$dir ."sendsms.php"; 
				//  $data= HelpingMethods::send_sms($params);
				DbMethods:: post_async($dir ,array('text'=>$params['text'],'phone'=>$params['phone'],'operator_id'=>$params['operator_id']));
				unset($params);
				$params="";
				$replay="";
		}
			
	
		 return "sent";	
		 	
	
}

/* 1:------------------------------method start here autoreply ------------------------------*/

static function post_async($url, $params) {
	
	//echo"mayar";
	
    foreach ($params as $key => &$val) {
      if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);
    $parts=parse_url($url);
    $fp = fsockopen($parts['host'],
        isset($parts['port'])?$parts['port']:80,
        $errno, $errstr, 30);
    $out = "POST ".$parts['path']." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "Connection: Close\r\n\r\n";
    if (isset($post_string)) $out.= $post_string;
    fwrite($fp, $out);
    fclose($fp);
}


/* 1:------------------------------method start here login ------------------------------*/
static function login($params) 
{
		global $con;	
		
		$password=sha1($params['password']);	  
        $query = "SELECT 
		*
		FROM `agent` as u 
		WHERE u.`username`='{$params['username']}' and u.`password`='{$password}'  AND `active`='0'  ";
		$result_1 = mysqli_query($con,$query) ;
		if (mysqli_error($con) != '')
		{
			return  "mysql_Error:-".mysqli_error($con);
			exit();
		}
		if (mysqli_num_rows($result_1) > 0)
			{	
			   $row = mysqli_fetch_assoc($result_1);	
			   if($row['username']==TRIM($params['username']) && $row['password']==TRIM($password))
				{
					
				  $_SESSION['agentid']=$row['id'];	
				  $_SESSION['agentusername']=$row['username'];
				  $_SESSION['type']=$row['type'];		
				}
				else
				{
					return 'not_valid_credential';
				}
			  }
		
		else
			{
				return 'not_valid_credential';
			}
	}
	
/* 1:------------------------------method start here logout 1------------------------------*/
 
		 
   static function logout()
   {
	   global $con;
		if(isset($_SESSION['agentid']))
		unset($_SESSION['agentid']);
		
		return "logout";
	}

/* 1:------------------------------method start here sms ------------------------------*/
static function sms($params) 
{
	
	
	global $con;
	   
	    if($params['index']==1 )
		{
		  $index =0;
		}
		else
		{
		  $index = (($params['index'] * 5000) - 5000);
		}
		$index2=50;
		$result2=array();	
	   $query_1 = "SELECT
		*
		FROM  `sms` 
		ORDER BY `id`  DESC  LIMIT ".$index.",".$index2."";
		$result = mysqli_query($con,$query_1) ;
		if (mysqli_error($con) != '')
		{
			return  "mysql_Error:-".mysqli_error($con);
			exit();
		}
		if (mysqli_num_rows($result) > 0) 
		{	
			while ($rowv = mysqli_fetch_assoc($result)) 
			{
			   $result2[] = $rowv;
			}
			if (!empty($result2)) 
			{
			   return $result2;
			}	
		} 


	
	
}




/* 1:------------------------------method start here pending ------------------------------*/
static function pending($params) 
{
		global $con;
		$pending="";
	    $query_1 = "SELECT
		count(*) as pendinree
		FROM  `sms` 
		where `id` > '{$params['id']}'   AND `status`='0' AND `read`='0'    AND  `from` NOT IN (SELECT `from` FROM `agent`  WHERE `from` IS NOT NULL) ";
		$result = mysqli_query($con,$query_1) ;
		if (mysqli_error($con) != '')
		{
			return  "mysql_Error:-".mysqli_error($con);
			exit();
		}
		if (mysqli_num_rows($result) > 0) 
		{	
			$row = mysqli_fetch_assoc($result);	
			if($row['pendinree'] > 0)
			$pending= $row['pendinree'];
		} 
		
		
		$rsultf=array();
		$query_2 = "SELECT `id`,`from` FROM `agent`  WHERE `from` IS NOT NULL ORDER BY id DESC LIMIT 0,200";
		$result2 = mysqli_query($con,$query_2) ;
		if (mysqli_error($con) != '')
		{
			return  "mysql_Error:-".mysqli_error($con);
			exit();
		}
		if (mysqli_num_rows($result2) > 0) 
		{	
			while ($rowv = mysqli_fetch_assoc($result2)) 
			{
				$rowv['pending']=$pending;
				$rsultf[]=$rowv;
				
			}
			
			return $rsultf;
		}
		else
		{
			$rowv=array();
			$rowv['from']='';
			$rowv['pending']=$pending;
			$rsultf[]=$rowv;
			return $rsultf;
		}
		
		
		

}


/* 1:------------------------------method start here pending2 ------------------------------*/
static function pending2($params) 
{
		global $con;
	    $query_1 = "SELECT
		*
		FROM  `sms` 
		where `id` > '{$params['id']}' AND `from` = '{$params['from']}' ORDER BY `id` DESC ";
		$result = mysqli_query($con,$query_1) ;
		if (mysqli_error($con) != '')
		{
			return  "mysql_Error:-".mysqli_error($con);
			exit();
		}
		if (mysqli_num_rows($result) > 0) 
		{
			$content="";
			$i=0;	
			$maxid="";
			while ($rowv = mysqli_fetch_assoc($result)) 
			{
				 $query_n1 = "UPDATE `sms` SET  
				`agent_id`='{$_SESSION['agentid']}',
				`read`='1'
				WHERE `id`='{$rowv['id']}'" ;
				$result_n1 = mysqli_query($con,$query_n1) ;
				
				if($i=='0')
				{
					$maxid= $rowv['id'];
				}
			   $rowv['maxid']=$maxid;
			   $rowv['text']=str_ireplace("Slb","",$rowv['text']);
			   $result2[] = $rowv;
			   
			   $i++;
			}
			if (!empty($result2)) 
			{
			   return $result2;
			}	
		} 
		

}


/* 1:------------------------------method start here getPrivacyT 1------------------------------*/
	 static function getSMS_session_id() 
	 {
		global $con;
		/* $query = "SELECT  `session_id` 
		FROM `sms_session` WHERE `session_id` !='' ";
		$result = mysqli_query($con,$query) ;
		if (mysqli_error($con) != '')
		{
			return  "mysql_Error:-".mysqli_error($con);
			exit();
		}
		if (mysqli_num_rows($result) > 0)
			{
				$row = mysqli_fetch_assoc($result);
				$session_id=  $row['session_id'];
				return $session_id;
			}
			
			else
			{
				
				$session_id='';
				$session_id= HelpingMethods::get_sms_session_id();
				
				$query_s = "INSERT INTO `sms_session` SET  
				`session_id`='{$session_id}'" ;
				$result_no = mysqli_query($con,$query_s) ;
				if (mysqli_error($con) != '')
				{
				  return  "mysql_Error:-".mysqli_error($con);
				  exit();
				}
				
				return $session_id;
			}*/
			
			
			$session_id= HelpingMethods::get_sms_session_id();
			return $session_id;
	 }


/* 1:------------------------------method start here pending ------------------------------*/
static function broadcast($params) 
{
		global $con;
		
			if($params['group_id'] !='')
			{
				$groupIds = explode(',', $params['group_id']);
				$query = "SELECT GROUP_CONCAT(phone) as phone FROM group_members WHERE ";
				$gdc = 0;
				foreach ($groupIds as $gid) {
					if ($gdc > 0) {
						$query  .= " OR ";
					}
					$query  .= "`group_id`='{$gid}'";
					$gdc++;
				}
				$result = mysqli_query($con,$query) ;
				if (mysqli_error($con) != '')
				{
					return  "mysql_Error:-".mysqli_error($con);
					exit();
				}
				if (mysqli_num_rows($result) > 0)
					{
						$row = mysqli_fetch_assoc($result);
						$params['phone']=$row['phone'];
					}
			}
			else
			{
				$params['phone']=$params['commaseparatedn'];
			}
			
			
			
			  $dbfrom=array();
			  $dboperator=array();
			  $dbagent_id=array();
			  $dbmatchresult=array();
			  $query2 = "SELECT GROUP_CONCAT(DISTINCT `from`) as phonec, GROUP_CONCAT(DISTINCT `agent_id`) as agent_idd ,  GROUP_CONCAT(DISTINCT `operator`) as operator 
			  FROM `sms` 
			  WHERE `from` IN(".$params['phone'].") ";
				$result2 = mysqli_query($con,$query2) ;
				if (mysqli_error($con) != '')
				{
					return  "mysql_Error:-".mysqli_error($con);
					exit();
				}
				if (mysqli_num_rows($result2) > 0)
					{
						 $row = mysqli_fetch_assoc($result2);
						 $phonec=$row['phonec'];
						 $operator=$row['operator'];
						 $agent_idd=$row['agent_idd'];
						 $dbfrom=explode(",", $phonec);
						 $dboperator=explode(",",$operator);
						 $dbagent_id=explode(",",$agent_idd);
					}
			
			
			$phone =explode(",",$params['phone']);
			$phone = array_values(array_unique(array_filter($phone)));
			$concat="";
			
			for($i=0;$i<count($phone);$i++)
			{
				if (preg_match("/^((\92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{12}$|^\d{4}-\d{7}$/",$phone[$i]))
						{
							if (in_array($phone[$i], $dbfrom))
							{
								$key = array_search($phone[$i], $dbfrom); // $key = 2;
								$opertaorid=$dboperator[$key]; //bar
								$dbagent_id=$dbagent_id[$key]; //bar
								
							
								$dbmatchresult[] ="('".$phone[$i]."', '".$opertaorid."', '".$params['text']."','1', '".$dbagent_id."')";
							}
							
							$concat.=$phone[$i];
							$concat.=',';
						}
			}
			
			$dbmatchresult = "INSERT INTO `sms` (`from`, `operator`,`text`,`send` ,`agent_id`) VALUES ". implode(',', $dbmatchresult);
			mysqli_query($con,$dbmatchresult) ;
			
			$concat= rtrim($concat,',');  
			$params['phone']=$concat;
			$params['text']= $params['text'];
			
			if($params['operator_id'] !='')
			{
			   $params['operator_id']="&operator_id=".$params['operator_id']."";
			}
			else
			{
				//$params['operator_id']="&operator_id=2";
				$params['operator_id']="";
			}
			
			
			$group_id="";
			if($params['group_id'] !='')
			{
			$group_id="`group_id`='{$params['group_id']}',";	
			}
			
			if (!isset($_SESSION['agentid'])) {
				$_SESSION['agentid'] = 1;
			}
			$query_s = "INSERT INTO `braodcast` SET  
			".$group_id."
			`agent_id`='{$_SESSION['agentid']}',
			`text`='{$params['text']}'" ;
			$result_no = mysqli_query($con,$query_s) ;
			if (mysqli_error($con) != '')
			{
			return  "mysql_Error:-".mysqli_error($con);
			exit();
			}
			$braodcast_id=mysqli_insert_id($con);	
			
			if($params['commaseparatedn'] !='')
			{
				$query_b = "INSERT INTO `manuallynumbers` SET  
				`braodcast_id`='{$braodcast_id}',
				`phone`='{$params['commaseparatedn']}'" ;
				$result_b = mysqli_query($con,$query_b) ;
				if (mysqli_error($con) != '')
				{
				  return  "mysql_Error:-".mysqli_error($con);
				  exit();
				}
			}
		
		  
		    $phonenumbers = array();
		    $phonenumbers = explode(",",$params['phone']) ;
			//$params['text']=$params['text']."<br>Reply with <slb> space <text message>";
			$params['text']=$params['text'];
			 foreach($phonenumbers as $phones)
			   {
					$path = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
					$file = substr( strrchr( $path, "/" ), 1) ; 
					$dir = str_replace( $file, '', $path ) ;
					$dir =$dir ."sendsms.php"; 
					DbMethods:: post_async($dir ,array('text'=>$params['text'],'phone'=>$phones,'operator_id'=>$params['operator_id']));
			   }
			
			return  true;
					

	//return "sended";
	
}

// Scheduler broadcast messages

static function broadcastltr($params) {
	global $con;

	if (!isset($params['group_id'])) {
		$params['group_id'] = '';
	}

	$old_date_timestamp = strtotime($params['datetime']);
	$new_date = date('Y-m-d H:i:s', $old_date_timestamp); 
	$query = "INSERT INTO `scheduler_data` (`commaseparatedn`, `group_id`,`message`,`datatimestr` ,`datetime`,`method`,`url`) VALUES ('".$params['commaseparatedn']."','".$params['group_id']."', '".$params['text']."','".$new_date."', '".strtotime($params['datetime'])."', '".$params['method']."', '".$params['url']."')";

	$inserted = mysqli_query($con,$query) ;
	if (mysqli_error($con) != '')
	{
		return  "mysql_Error:-".mysqli_error($con);
		exit();
	}
	$scheduler_data_id = mysqli_insert_id($con);

	$query_sch = "INSERT INTO `scheduler` (`name`, `time_interval`,`fire_time`,`run_only_once` ,`currently_running`,`scheduler_data_id`) VALUES ('Scheduler','".strtotime($params['datetime'])."', '".strtotime($params['datetime'])."','1', '0', '".$scheduler_data_id."')";

	$inserted = mysqli_query($con,$query_sch) ;
	if (mysqli_error($con) != '')
	{
		return  "mysql_Error:-".mysqli_error($con);
		exit();
	}

	$scheduler_id = mysqli_insert_id($con);
	return  $rand;
}



/* 1:------------------------------method start here pending ------------------------------*/
static function replay($params) 
{
		global $con;
			   
			$params['phone']='';
		
			$params['text']= $params['text'];
			$params['phone']=$params['from'];
			if (preg_match("/^((\92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{12}$|^\d{4}-\d{7}$/",$params['phone']))
			{
				if($params['operator'] !='')
				{
				$params['operator_id']="&operator_id=".$params['operator']."";
				}
				else
				{
				  $params['operator_id']="";
				}
			
			  
			$path = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
			$file = substr( strrchr( $path, "/" ), 1) ; 
			$dir = str_replace( $file, '', $path ) ;
			$dir =$dir ."sendsms.php"; 
			//$params['text1']= $params['text']."<br>Reply with <slb> space <text message>";
			$params['text1']= $params['text'];
			DbMethods:: post_async($dir ,array('text'=>$params['text1'],'phone'=>$params['phone'],'operator_id'=>$params['operator_id']));
			  
			}
			
			
				 $query_n1 = "INSERT INTO `sms` SET  
				`agent_id`='{$params['agentid']}',
				`parent_id`='{$params['parent_id']}',
				`text`='{$params['text']}',
				`from`='{$params['from']}',
				`operator`='{$params['operator']}',
				`send`='1',
				`status`='1',
				`read`='1'
				 " ;
				$result_n1 = mysqli_query($con,$query_n1) ;
				if (mysqli_error($con) != '')
				{
				  return  "mysql_Error:-".mysqli_error($con);
				  exit();
				}
		
			
			

			
			 return  'send';
					
				
		
		 
	 

	//return "sended";
	
}



/* 1:------------------------------method start here groupdelete ------------------------------*/
static function groupdelete($params) 
{
		global $con;
	     $query_1 = "DELETE FROM `group` WHERE `id`='{$params['id']}' ";
		$result = mysqli_query($con,$query_1) ;
		
		return "dleted";

}



/* 1:------------------------------method start here groupdelete ------------------------------*/
static function ConfirmagentDelete($params) 
{
		global $con;
		if($params['active']!='1')
		$params['active']='0';
	    $query_1 = "UPDATE  `agent` SET `active`='{$params['active']}'  WHERE `id`='{$params['id']}' ";
		$result = mysqli_query($con,$query_1) ;
		
		return "dleted";

}



/* 1:------------------------------method start here groupdelete ------------------------------*/
static function recipients($params) 
{
	
	global $con;
		
			$query_1 = "SELECT
			*
			FROM  `braodcast`
			WHERE `id`='{$params['id']}' ";
			$result = mysqli_query($con,$query_1) ;
			if (mysqli_error($con) != '')
			{
				return  "mysql_Error:-".mysqli_error($con);
				exit();
			}
			if (mysqli_num_rows($result) > 0) 
			{	
			    $row = mysqli_fetch_assoc($result);
				$id=  $row['id'];
				$group_id=  $row['group_id'];
				
				if($group_id!='')
				{
				
					   $query_2 = "SELECT
						GROUP_CONCAT(`phone` SEPARATOR ',') as phone
						FROM  `group_members`
						WHERE `group_id`='{$group_id}' ";
						$result2 = mysqli_query($con,$query_2) ;
						if (mysqli_error($con) != '')
						{
							return  "mysql_Error:-".mysqli_error($con);
							exit();
						}
						if (mysqli_num_rows($result2) > 0) 
						{	
							$row2 = mysqli_fetch_assoc($result2);
							return $row2['phone'];
						 
						}
				}
				else
				{
					 $query_2 = "SELECT
						`phone`
						FROM  `manuallynumbers`
						WHERE `braodcast_id`='{$params['id']}' ";
						$result2 = mysqli_query($con,$query_2) ;
						if (mysqli_error($con) != '')
						{
							return  "mysql_Error:-".mysqli_error($con);
							exit();
						}
						if (mysqli_num_rows($result2) > 0) 
						{	
							$row2 = mysqli_fetch_assoc($result2);
							return $row2['phone'];
						 
						}
				}
			 
			}

}


/* END-----------------------------END END END END------------------------------END*/

}


?>
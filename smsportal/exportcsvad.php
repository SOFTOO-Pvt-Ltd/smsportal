<?php
include_once '../api/dbConfig.php';
ob_start();
$search_p="";
$search_pp="";
$fromtodate_a="";
$tripid_a="";
$agentid_a="";
$fromnumber_a="";
$tat_a="";
$link="";
if (!empty($_REQUEST['fromdate_a']))
	{
		$date = strtotime($_REQUEST['fromdate_a']);
		$_REQUEST['fromdate_a']=  date('Y-m-d', $date);
		
		if(!empty($_REQUEST['to_a']))
		{
			$date1 = strtotime($_REQUEST['to_a']);
			$_REQUEST['to_a']=  date('Y-m-d', $date1);
		}
		else
		{
			$_REQUEST['to_a']=  date('Y-m-d');
		}
	    $fromtodate_a=" AND  (CAST(`sms`.`datatime` AS DATE) BETWEEN  '{$_REQUEST['fromdate_a']}' AND '{$_REQUEST['to_a']}')";
		$link="&fromdate_a=".$_REQUEST['fromdate_a']."&to_a=".$_REQUEST['to_a'];
	}
if(!empty($_REQUEST['tripid_a'])) 
	{
		$tripid_a=" AND `sms`.`text` LIKE  '%".$_REQUEST['tripid_a']."%'";
		$link.="&tripid_a=".$_REQUEST['tripid_a'];
	}	
if(!empty($_REQUEST['fromnumber_a'])) 
	{
		if (preg_match("/^((\92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/",$_REQUEST['fromnumber_a']))
		{
			$fromnumber_a=" AND `sms`.`from`='{$_REQUEST['fromnumber_a']}'";
			$link.="&fromnumber_a=".$_REQUEST['fromnumber_a'];
		}
	}
if(!empty($_REQUEST['agentid_a'])) 
	{
		 $myArray = explode(',', $_REQUEST['agentid_a']);
		 $str = implode (", ", $myArray);
		 $agentid_a=" AND `sms`.`agent_id` IN ($str)";
		 $link.="&agentid_a=".$_REQUEST['agentid_a'];
	}
if(!empty($_REQUEST['tat_a'])) 
	{
		$link.="&tat_a=".$_REQUEST['tat_a'];
	}			
$search_p=$fromtodate_a.$tripid_a.$fromnumber_a.$agentid_a.$tat_a;	
 $search_pp=substr(strstr($search_p," "), 4);


global $con;
$SQL = "SELECT 
`sms`.`id`,
`sms`.`from`,
`sms`.`datatime`,
`agent`.`username`,
`sms`.`send`,
`sms`.`text`
FROM  `sms` LEFT OUTER JOIN `agent` ON(`sms`.`agent_id`=`agent`.`id`) 
WHERE `sms`.id IN (
SELECT MAX(id)
FROM sms
GROUP BY `from`
)  ".$search_p." ORDER BY `sms`.id DESC ";
$header = '';
$result ='';
$exportData = mysqli_query($con,$SQL) ;
$header .="SMS ID" . "\t";;
$header .="From Number" . "\t";
$header .="Recieved Date" . "\t";
$header .="Agent" . "\t";
$header .="Send From" . "\t";
$header .="Message" . "\t";

$result .= trim( $header ) . "\n";


if (mysqli_num_rows($exportData) > 0) 
 {
while( $row = mysqli_fetch_assoc( $exportData ) )
{
	$row['text']=str_ireplace("Slb","",$row['text']);
	if($row['send']=='0')
	{
		$row['send']="Recipient";
	}
	
	if($row['send']=='1')
	{
		$row['send']="Agent";
	}
	
    $line = '';
    foreach( $row as $value )
    { 
        if ( ( !isset( $value ) ) || ( $value == "" ) )
        {
            $value = "\t";
        }
        else
        {
            $value = str_replace( '"' , '""' , $value );
            $value = '"' . $value . '"' . "\t";
        }
        $line .= $value;
    }
    $result .= trim( $line ) . "\n";
}
$result = str_replace( "\r" , "" , $result );
 
if ( $result == "" )
{
    $result = "\nNo Record(s) Found!\n";                        
}
header("Content-type: text/csv; charset=utf-8"); 
header("Content-type: application/octet-stream");
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=export.xls");
header("Pragma: no-cache");
header("Expires: 0");

//print "$header\n$result";
print chr(255).chr(254).iconv("UTF-8", "UTF-16LE//IGNORE", $result); 
 

}
ob_end_flush();  

?>
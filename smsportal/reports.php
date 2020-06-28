<?php

include_once 'header.php';

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
if(!empty($_REQUEST['fromnumber_a1'])) 
	{
		if (preg_match("/^((\92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/",$_REQUEST['fromnumber_a1']))
		{
			$fromnumber_a=" AND `sms`.`from`='{$_REQUEST['fromnumber_a1']}'";
			$link.="&fromnumber_a1=".$_REQUEST['fromnumber_a1'];
		}
	}
	
if(!empty($_REQUEST['fromnumber_a'])) 
		{
		   $link.="&fromnumber_a=".$_REQUEST['fromnumber_a'];
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

?>

<div class="loginPage">
    <div class="container">
        <nav class="mainNav">
            
           <ul>
                <li>Welcome, <?=$_SESSION['agentusername']?></li>
                <?php  if($_SESSION['type']=='1') { ?>
                   <li><a href="advancesearch?<?=$link?>" class="btn btn-green">Reports</a></li>
                   <li><a href="agents" class="btn btn-green">Agents</a></li>
                   <li><a href="alerts" class="btn btn-green">TAT</a></li>
                   <li><a href="group" class="btn btn-green">Groups</a></li>
                   <li><a class="btn btn-orange setreplay">Set Reply</a></li>
                 <?php  } ?>
                 <li><a class="btn btn-orange btn-broadcast">Create Message</a></li>
                <li><a href="broadcast" class="btn btn-green">Sent Messages</a></li>
                <li><a class="btn btn-darkgrey" onClick="logout();">Sign Out</a></li>
            </ul>
        </nav>
        <div class="pageContent">
            <h1><a href="advancesearch?<?=$link?>" class="custom-icon-left-arrow"></a>Back to Advance Search</h1>
           
            <div class="tableContainer">
             <form action="exportcsv.php" method="get" >
            <input type="hidden" name="fromdate_a" value="<?=  isset($_REQUEST['fromdate_a']) ? $_REQUEST['fromdate_a'] : ""; ?>" />
            <input type="hidden" name="to_a" value="<?=  isset($_REQUEST['to_a']) ? $_REQUEST['to_a'] : ""; ?>" />
            <input type="hidden" name="tripid_a" value="<?=  isset($_REQUEST['tripid_a']) ? $_REQUEST['tripid_a'] : ""; ?>" />
            <input type="hidden" name="fromnumber_a" value="<?=  isset($_REQUEST['fromnumber_a']) ? $_REQUEST['fromnumber_a'] : ""; ?>" />
            <input type="hidden" name="agentid_a" value="<?=  isset($_REQUEST['agentid_a']) ? $_REQUEST['agentid_a'] : ""; ?>" />
            <input type="hidden" name="tat_a" value="<?=  isset($_REQUEST['tat_a']) ? $_REQUEST['tat_a'] : ""; ?>" />
            <input type="submit" class="btn btn-lightgreen" name="submit" value="export CSV" />
            </form>
                <div class="conversationContainer">
                    <ul class="smsSender">
                        <li class="square yellow">Agent</li>
                        <li class="square red">Recipient</li>
                        
                        
                    </ul>
                    
                    <div class="smsContentArea">
                        
                    <?php
					global $con;
					$params['index']=1;
					$total='';
					$search="";
					$search_2='';
					$searchq="";
					$index=0;
					$index2=10;
					$pagen=1;
					$orderby="ASC";
					$order=0;
					$pattern = '/[^0-9]/';
						if(isset($_REQUEST['page'])) 
						{
							$page = preg_match($pattern, $_REQUEST['page']);
							if ($_REQUEST['page'] == ''   ||  ($page > 0 || $_REQUEST['page'] ==0)) 
							{  $index=0;	} 
							else
							{ 
								if($_REQUEST['page']==1)
								{
									$index=0;
									$pagen=1;
								}	
								else
								{
									$index = (($_REQUEST['page'] * 10) - 10);
									$pagen=($_REQUEST['page']);
								}
							}
						}
							 $query_1 = "SELECT
							*,`agent`.`username`
							FROM  `sms` LEFT OUTER JOIN `agent` ON(`sms`.`agent_id`=`agent`.`id`) 
							WHERE ".$search_pp."  
							ORDER BY sms.id DESC LIMIT ".$index.",".$index2."";
							$result = mysqli_query($con,$query_1) ;
							if (mysqli_error($con) != '')
							{
							return  "mysql_Error:-".mysqli_error($con);
							exit();
							}
							if (mysqli_num_rows($result) > 0) 
							{
								$i=0;	
								while ($rowv = mysqli_fetch_assoc($result)) 
								{
									$gusername="";
									if($rowv['username'])
									{
										$gusername=$rowv['username'];
									}
									
									if($rowv['send']=='0')
									{
										$send='<span class="sender">Recipient to Agent ('.$gusername.') ('.$rowv['datatime'].') </span>';
										$agentt='driver';
									}
									else
									{
										$send='<span class="sender">Agent to Recipient ('.$gusername.') ('.$rowv['datatime'].') </span>';
										$agentt='agent';
									}
								
									echo '<div class="smsUnit '.$agentt.'">
									'.$send.' 
									  <div class="sms">'. str_ireplace("Slb","",$rowv['text']).'</div>
									</div>';
									$i++;
								  }
							}
					?>    
                        
                    </div>
                    <ul class="table_pagination" >
						<?php
                        $statement=" `sms` WHERE  ".$search_pp."";
                        echo '<div class="paginationn" >';
						 echo pagination($statement,10,$pagen,$url='?'.$link.'&');
                        echo '</div>';
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once 'footer.php';

?>



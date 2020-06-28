<?php
include_once 'header.php';


 $path = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].":3001/";
echo "<script> var hostnamep='".$path ."';</script>";
$id='';
$pattern = '/[^0-9]/';
$from='';
$latesincomingsms='';
$text='';
$datetime='';
$operator='';
$admincheck='';
if(isset($_REQUEST['id'])) 
{
	$id = preg_match($pattern, $_REQUEST['id']);
	if ($_REQUEST['id'] == ''   ||  ($id > 0 || $_REQUEST['id'] ==0)) 
	{ 
	   header("Location: sms.php");
	} 
	else
	{ 
	  $id =$_REQUEST['id'];
	}
}

    $query_1_t = "SELECT *,
	(SELECT `from` FROM `agent` WHERE `from`=fsms .`from` AND `id` !='{$_SESSION['agentid']}'  ORDER BY id LIMIT 1 ) as checkagents,
	(SELECT `text` FROM `sms` WHERE `send`='0' AND `from`=fsms.`from` ORDER BY `id` DESC LIMIT 1 ) as latesincomingsms
	FROM  `sms` as fsms 
	WHERE `id`='{$id}'";
	$result_t = mysqli_query($con,$query_1_t) ;
	if (mysqli_error($con) != '')
	{
	return  "mysql_Error:-".mysqli_error($con);
	exit();
	}
	if (mysqli_num_rows($result_t) > 0) 
	{	
	    $row_t = mysqli_fetch_assoc($result_t);	
		if($row_t['checkagents'])
		{
			echo "
			<script type=\"text/javascript\">
		     	  alert('The following Recipient is busy with another agent');
				  window.location='sms.php';
				   exit();
			</script>
			";
			exit();
		}
		
		if( $row_t['agent_id']==NULL)
		{  
			$query_n1 = "UPDATE `sms` SET  
			`agent_id`='{$_SESSION['agentid']}',
			`status`='1',
			`read`='1'
			WHERE `id`='{$id}'" ;
			$result_n1 = mysqli_query($con,$query_n1) ;
			if (mysqli_error($con) != '')
			{
			 return  "mysql_Error:-".mysqli_error($con);
			 exit();
			}
		}
		else
		{
			
			$query_n1 = "UPDATE `sms` SET  
			`status`='1',
			`read`='1'
			WHERE `id`='{$id}'" ;
			$result_n1 = mysqli_query($con,$query_n1) ;
			if (mysqli_error($con) != '')
			{
			 return  "mysql_Error:-".mysqli_error($con);
			 exit();
			}
		}
		
			$latesincomingsms=$row_t['latesincomingsms'];
			$from=$row_t['from'];
			$text=$row_t['text'];
			$datetime=$row_t['datatime'];
			$operator=$row_t['operator'];
			
			echo "
			<script type=\"text/javascript\">
		     	 var frompho=".$from.";
			</script>
			";
	}
	
	else
		{
			echo '
			<script type="text/javascript">
			 window.location="sms.php";
			</script>
			';
			exit;
	}
	
$error='';
echo "
<script type=\"text/javascript\">
var idofagent2='".$id."';
</script>
";
if($operator=='')
$operator='1';
?>

<div class="loginPage">
    <div class="container">
        <nav class="mainNav">
           
           <ul>
                <li>Welcome, <?=$_SESSION['agentusername']?></li>
                <?php  if($_SESSION['type']=='1') { ?>
                   <li><a href="advancesearch" class="btn btn-green">Reports</a></li>
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
            <h1><a href="sms" class="custom-icon-left-arrow"></a>SMS Details from </h1>
           
            <div class="tableContainer">
          
              
                <form action="" class="incomingOutgoingSms" id="incomingOutgoingSms" method="post">
                <input type="hidden" name="agentid" id="agentid" value="<?=$_SESSION['agentid']?>" />
                <input type="hidden" name="from" id="from" value="<?=$from?>" />
                 <input type="hidden" name="parent_id" id="parent_id" value="<?=$id?>" />
                  <input type="hidden" name="operator" id="operator" value="<?=$operator?>" />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="msgWrapper">
                                <h6>Incoming Message, From <strong><?=$from?></strong></h6>
                                <div class="msgContent">
                                 <div class="form-group">
                                    <textarea id="maxicsmstxt" readonly><?= str_ireplace("Slb","",$latesincomingsms)?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="msgWrapper">
                                <h6>Outgoing Message</h6>
                                <button class="btn btn-lightgreen" name="outbtn">Send</button>
                                <div class="msgContent">
                                  <div class="form-group">
                                    <textarea maxlength="700" name="ougoing" id="ougoing"></textarea>
                                   <span class="alertMsg userAlert" style="display:none;">Outgoing is required, Max Length 700.</span>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
							`sms`.*,`agent`.`username`,
							(SELECT MAX(id) FROM sms  WHERE `from`='{$from}' ) as maxxid
							FROM  `sms` LEFT OUTER JOIN `agent` ON(`sms`.`agent_id`=`agent`.`id`) 
							WHERE sms.`from`='{$from}'   
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
									
									$query_n1 = "UPDATE `sms` SET  
									`read`='1'
									WHERE `id`='{$rowv['id']}'" ;
									$result_n1 = mysqli_query($con,$query_n1) ;
									
									if($i=='0' && $index==0)
									{
										//echo $rowv['maxxid'];
										echo "
										<script type=\"text/javascript\">
										var idofagent2=".$rowv['maxxid'].";
										</script>
										";
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
                        $statement="  `sms` WHERE `from`='{$from}'    ";
                        //$sort="DESC";
						
                        echo '<div class="paginationn" >';
                        echo pagination($statement,10,$pagen,$url='?id='.$_REQUEST['id'].'&');
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



<script src="assets/js/socket.io.js"></script>
<script>

//alert(hostnamep);
var id=<?=$_SESSION['agentid']?>;
var from=<?=$from?>;
var socket = io(hostnamep, { query:"id="+id+"&from="+from+""});


socket.on('connect', function() {console.log('Connected');});

socket.on('error', function() {console.log('error'); setTimeout(function() {window.location="sms.php";}, 2000);  });
socket.on('connect_error', function() {console.log('connect error'); setTimeout(function() {window.location="sms.php";}, 2000);  });
socket.on('disconnect', function() {console.log('Disconnected');  setTimeout(function() {window.location="sms.php";}, 2000);  });


</script>

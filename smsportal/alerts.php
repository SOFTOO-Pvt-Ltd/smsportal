<?php
include_once 'header.php';

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
            <h1><a href="sms" class="custom-icon-left-arrow"></a>Back to Dashboard</h1>
            <div class="tableContainer">
                <div class="contHeader">
                    <h2><!--Alert List--> Turn around time mean how much time after it was read</h2>
                    
                </div>
                <div class="contBody">
                    <div class="tableWrapper">
                        <table class="table table-actions dataTable">
                            <thead>
                            <tr>
                                <th>SMS ID</th>
                                <th>From Number</th>
                                <th>Recieved Date</th>
                                 <th>Agent ID</th>
                                <th>Message</th>
                            </tr>
                            </thead>
                            <tbody>
                            
                            <?php
								global $con;
								$params['index']=1;
								$total='';
								$search="";
								$search_2='';
								$searchq="";
								$index=0;
								$index2=20;
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
												$index = (($_REQUEST['page'] * 20) - 20);
												$pagen=($_REQUEST['page']);
											}
										}
									}
									
							
								$query_1 = "SELECT *
								FROM `sms`
								WHERE id IN (
								SELECT MAX(id)
								FROM sms
								WHERE  `parent_id` IS NULL  
								GROUP BY `from`
								) 
								AND  `parent_id` IS NULL  AND `id` NOT IN(SELECT `parent_id` FROM `sms`  where `parent_id` IS NOT NULL )
								AND TIMESTAMPDIFF(MINUTE,datatime,NOW()) > 15 AND `send`='0'
								UNION
								
								SELECT * 
								FROM `sms` as s
								WHERE 
								id IN (
								SELECT MAX(id)
								FROM sms 
								WHERE  `parent_id` IS  NULL  
								GROUP BY `from`
								) 
								AND  `parent_id` IS  NULL  AND `id` IN(SELECT `parent_id` FROM `sms`  where `parent_id`  IS NOT NULL )  
								AND TIMESTAMPDIFF(MINUTE,datatime,(SELECT  `datatime` FROM `sms` WHERE   `parent_id`=s.`id` ORDER BY `id` DESC LIMIT 1 )) > 15
								AND `send`='0' 
								ORDER BY id DESC LIMIT 0,100";
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
											
											$agentid="";$username="Pending";
											 $query_1_t = "SELECT `agent_id`,`agent`.`username`
											FROM  `sms` LEFT OUTER JOIN `agent` ON(`sms`.`agent_id`=`agent`.`id`)  
											WHERE  `sms`.`from` =".$rowv['from']."  AND `agent_id` !=''
											ORDER BY `sms`.id DESC LIMIT 1";
											$result_t = mysqli_query($con,$query_1_t) ;
											if (mysqli_error($con) != '')
											{
											return  "mysql_Error:-".mysqli_error($con);
											exit();
											}
											if (mysqli_num_rows($result_t) > 0) 
											{	
											  $row_t = mysqli_fetch_assoc($result_t);	
											  $agentid=  $row_t['agent_id'];
											  $username=  $row_t['username'];
											}
											
											
										
										    $astart=' href="detail?id='.$rowv['id'].'"';
											echo ' <tr>
											<td  ><a '.$astart.'>'.$rowv['id'].'</a></td>
											<td  ><a '.$astart.'>'.$rowv['from'].'</a></td>
											<td  ><a '.$astart.'>'.$rowv['datatime'].'</a></td>
											<td  ><a '.$astart.'>'. $username.'</a></td>
											<td  title='.str_ireplace("Slb","",$rowv['text']).'><a '.$astart.'>
											<span class="textLimit">'.str_ireplace("Slb","",$rowv['text']).'</span></a>
											</td>
                                           
											</tr>';	
											
										}
									}
                                    
                             ?>
                            
                            
                            </tbody>
                        </table>
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include_once 'footer.php';
?>

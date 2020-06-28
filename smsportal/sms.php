<?php

include_once 'header.php';

		$pattern = '/[^0-9]/';
		$all='';
		$pending='';
		$blocked='';
		$status='0';
		$total_r='0';
		$pending_r='0';
		$blocked_r='0';
		
		echo "
		<script type=\"text/javascript\">
		var idofagent='0';
		</script>
		";
		$query_1_t = "SELECT `id` ,`agent_id`
		FROM  `sms` 
		WHERE  `parent_id`IS NULL  
		ORDER BY id DESC LIMIT 1";
		$result_t = mysqli_query($con,$query_1_t) ;
		if (mysqli_error($con) != '')
		{
		return  "mysql_Error:-".mysqli_error($con);
		exit();
		}
		if (mysqli_num_rows($result_t) > 0) 
		{	
		   $row_t = mysqli_fetch_assoc($result_t);	
			echo "
			<script type=\"text/javascript\">
			var idofagent=".$row_t['id'].";
			</script>
			";
		}
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
           
            <div class="tableContainer">
                <div class="contHeader">
                    <h2>Dashboard  <a  href="sms" class="btn btn-red" style="display:none;"></a></h2>
                  
                    <div class="btnList">
                      <div class="form-group addNumbers">
                            <input class="form-control" id="search" type="text" placeholder="Search text, number">
                            <a class="btn btn-transparent" onclick="searchr()"><i class="custom-icon-search"></i></a>
                        </div>
                        <a  href="sms" class="btn btn-transparent ">Refresh List</a>
                    </div>
                </div>
                <div class="contBody">
                   
                    <div class="tableWrapper">
                        <table class="table table-actions dataTable">
                            <thead>
                            <tr>
                                <th>SMS ID</th>
                                <th>From Number</th>
                                <th>Recieved Date</th>
                                <th>Last Followed by Agent</th>
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
											
											$query_1="SELECT `sms`.* ,`agent`.`username`
											FROM `sms` LEFT OUTER JOIN `agent` ON(`sms`.`agent_id`=`agent`.`id`) 
											WHERE `sms`.id IN (
											SELECT MAX(id)
											FROM sms
											GROUP BY `from`
											) ORDER BY `sms`.id DESC LIMIT ".$index.",".$index2."";
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
													    $astart='';$disbale='';$lock='';$bold='';$bag='';
														$astart=' href="detail?id='.$rowv['id'].'"';
														
														if($rowv['read']=='0')
														{
															$lock="Pending";
															$bold=' style="font-weight:500;"';
															$bag='style="background-color:pink;"';
														}
														else
														{
															$lock='Click to check the availablility';
														}
													
													$agid='Pending';
													if( $rowv['username'] !='')
													$agid= $rowv['username'];
													
                                                    
                                                    ?>
                                                    <tr title="<?=$lock?>"  <?=$bag?> >
                                                    <td class="<?= $rowv['from']?>"  <?=$bold?> > 
                                                    <a class="<?= $rowv['id']?>"  <?=$astart ?>><?= $rowv['id']?></a> </td>
                                                    <td class="<?= $rowv['from']?>"  <?=$bold?>>
                                                    <a class="<?= $rowv['id']?>"  <?=$astart ?>><?= $rowv['from']?></a></td>
                                                    <td class="<?= $rowv['from']?>"  <?=$bold?> style="width: 190px;">
                                                    <a class="<?= $rowv['id']?>"  <?=$astart ?>><?= $rowv['datatime']?></a></td>
                                                    <td  class="<?= $rowv['from']?>"  <?=$bold?>>
                                                    <a class="<?= $rowv['id']?>"id="id_<?= $rowv['from']?>" 
													<?=$astart ?>><?= $agid?></a></td>
                                                    <td class="<?= $rowv['from']?>"  <?=$bold?> title="<?= $rowv['text']?>">
                                                    <a class="<?= $rowv['id']?>"  <?=$astart ?>> 
                                                    <span class="textLimit"><?= str_ireplace("Slb","",$rowv['text'])?></span>
                                                    </a>
                                                    </td>
                                                    
                                                    </tr>
                                     <?php
                                                }
                                            }
                                    
                             ?>
                            
                            </tbody>
                        </table>
                    </div>
                    <ul class="table_pagination" >
						<?php
                        $statement="  `sms`  ";
                        //$sort="DESC";
						$slt="DISTINCT `from`";
                        echo '<div class="paginationn" >';
                        echo pagination($statement,20,$pagen,$url='?',$slt);
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

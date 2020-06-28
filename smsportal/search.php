<?php
include_once 'header.php';
$search_p="";
$search_pp="";
if(isset($_REQUEST['search'])) 
	{
		$search_p="AND (`text` LIKE  '%".$_REQUEST['search']."%'  OR `sms`.`from` LIKE  '%".$_REQUEST['search']."%' ) ";
		$search_pp="WHERE (`text` LIKE  '%".$_REQUEST['search']."%'  OR `from` LIKE  '%".$_REQUEST['search']."%' ) ";
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
            <h1><a href="sms" class="custom-icon-left-arrow"></a>Dashboard</h1>
            <div class="tableContainer">
                <div class="contHeader">
                    <h2>SMS List</h2>
                    <div class="btnList">
                     <div class="form-group addNumbers">
                            <input class="form-control" id="search" type="text" placeholder="Search text, number">
                            <a class="btn btn-transparent" onclick="searchr()"><i class="custom-icon-search"></i></a>
                        </div>
                        <a class="btn btn-red" style="display:none;">4</a>
                        <a  href="sms" class="btn btn-transparent">Refresh List</a>
                    </div>
                </div>
                <div class="contBody">
                    <ul class="tabs">
                        <!-- <li><a href="sms" class="<?=$all?>">All (<?=$total_r ?>)</a></li>
                       <li><a href="sms?status=1" class="<?=$pending?>">Pending (<?=$pending_r ?>)</a></li>
                        <li><a href="sms?status=2" class="<?=$blocked?>">Locked (<?=$blocked_r ?>)</a></li>-->
                    </ul>
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
											)  ".$search_p." ORDER BY `sms`.id DESC LIMIT ".$index.",".$index2."";
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
													  $astart='';$disbale='';$lock='';$bold='';
													  $astart=' href="detail?id='.$rowv['id'].'"';
													
													   if($rowv['read']=='0')
														{
															$lock="Pending";
															$bold=' style="font-weight:500;"';
															$bag='style="background-color:#eee;"';
														}
														else
														{
															$lock='Click to check the availablility';
														}
													
													$agid='Pending';
													if( $rowv['username'] !='')
													$agid= $rowv['username'];
													
                                                    
                                                    ?>
                                                    <tr title="<?=$lock?>" >
                                                      <td <?=$bold?> ><a  <?=$astart ?>><?= $rowv['id']?></a></td>
                                                      <td <?=$bold?>><a  <?=$astart ?>><?= $rowv['from']?></a></td>
                                                      <td <?=$bold?>><a  <?=$astart ?>><?= $rowv['datatime']?></a></td>
                                                       <td <?=$bold?>><a  <?=$astart ?>><?= $agid?></a></td>
                                                      <td <?=$bold?> title="<?= $rowv['text']?>"><a  <?=$astart ?>>
                                                      <span class="textLimit"><?= str_ireplace("Slb","",$rowv['text'])?></span></a>
                                                      </td>
                                                     
                                                    </tr>
                                     <?php
                                                }
                                            }
											else
											{
												echo "No result found.";
											}
                             ?>
                            </tbody>
                        </table>
                    </div>
                    <ul class="table_pagination" >
						<?php
                        $statement=" `sms`  ".$search_pp."";
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

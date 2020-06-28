<?php
include_once 'header.php';
$search_p="";
$search_pp="";
$fromtodate_a="";
$tripid_a="";
$agentid_a="";
$fromnumber_a="";
$tat_a="";
$link='';


$fromdate_a_1="";
$to_a_1="";
$tripid_a_1="";
$fromnumber_a_1="";
$agentid_a_1="";
$tat_a_1="";


if (!empty($_REQUEST['fromdate_a']))
	{
		
		$date = strtotime($_REQUEST['fromdate_a']);
		$_REQUEST['fromdate_a']=  date('Y-m-d', $date);
		
		if(!empty($_REQUEST['to_a']))
		{
			$date1 = strtotime($_REQUEST['to_a']);
			$_REQUEST['to_a']=  date('Y-m-d', $date1);
			$to_a_1=$_REQUEST['to_a'];
		}
		else
		{
			$_REQUEST['to_a']=  date('Y-m-d');
		}
		
		
	    $fromtodate_a=" AND  (CAST(`sms`.`datatime` AS DATE) BETWEEN  '{$_REQUEST['fromdate_a']}' AND '{$_REQUEST['to_a']}')";
		$link="&fromdate_a=".$_REQUEST['fromdate_a']."&to_a=".$_REQUEST['to_a'];
		
		$fromdate_a_1=$_REQUEST['fromdate_a'];
	}


	
if(!empty($_REQUEST['tripid_a'])) 
	{
		
		$tripid_a=" AND `sms`.`text` LIKE  '%".$_REQUEST['tripid_a']."%'";
		$link.="&tripid_a=".$_REQUEST['tripid_a'];
		$tripid_a_1=$_REQUEST['tripid_a'];
	}	

if(!empty($_REQUEST['fromnumber_a'])) 
	{
		if(!empty($_REQUEST['tripid_a'])) 
		{
		   $tripid_a=" AND  (`sms`.`from`  LIKE  '%".$_REQUEST['fromnumber_a']."%' OR  `sms`.`text` LIKE  '%".$_REQUEST['tripid_a']."%')";
		}
		else
		{
			$fromnumber_a=" AND `sms`.`from`  LIKE  '%".$_REQUEST['fromnumber_a']."%'";
		}
		
		
	    $link.="&fromnumber_a=".$_REQUEST['fromnumber_a'];
		$fromnumber_a_1=$_REQUEST['fromnumber_a'];
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
		/*$tat_a=$_REQUEST['tat_a'];*/
		$link.="&tat_a=".$_REQUEST['tat_a'];
		$tat_a_1="checked";
	}			


$search_p=$fromtodate_a.$tripid_a.$fromnumber_a.$agentid_a.$tat_a;	
$search_pp=substr(strstr($search_p," "), 4);
?>

<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="assets/css/bootstrap-select.min.css">


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
                    <div class="contHeader clearfix">
                    <form id="adsearch" action="" class="advancedSearchForm clearfix">
                        <div class="form-group width28">
                            <label>Select Date</label>
                            <input type='text' value="<?=  $fromdate_a_1; ?>" class="form-control width50" id='datetimepicker1' name="fromdate_a" placeholder="From"/>
                            <input type='text' value="<?=  $to_a_1; ?>" class="form-control width50" id='datetimepicker2' name="to_a" placeholder="To"/> 
                        </div>
                        <div class="form-group width15">
                            <label>Message</label>
                            <input class="form-control" value="<?= $tripid_a_1; ?>" type="text" name="tripid_a" placeholder="Message" maxlength="50">

                        </div>
                        <div class="form-group width20">
                            <label>CC Agent</label>
                             <input type="hidden" name="agentid_a" id="agentid_a" value="" />
                             <select id="cuisine-select" name="name" multiple="multiple" tabindex="-1" class="selectpicker" aria-hidden="true">
                              
                                  <?php
									 $query_1 = "SELECT
                                           `id`,
										   `username`
                                            FROM  `agent` 
                                            ORDER BY `id`  DESC  LIMIT 0,100";
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
													echo ' <option value="'.$rowv['id'].'">'.$rowv['username'].'</option>';
												}
											}
									?>
                            </select>

                        </div>
                        <div class="form-group width20">
                            <label>Mobile Number</label>
                            <input class="form-control" value="<?=  $fromnumber_a_1; ?>" type="text" name="fromnumber_a" placeholder="Add Number" maxlength="20">

                        </div>
                        <div class="form-group width7">
                            <label>TAT</label>
                            <div class="custom-form-wid radio">
                                <input id="forid11" name="tat_a" type="checkbox" <?=$tat_a_1;?>  value="1">
                                <label for="forid11"><i class="fa"></i></label>
                            </div>
                        </div>
                        <div class="form-group width10">
                            <button class="btn btn-lightgreen">Search</button>
                        </div>
                    </form>
                    
                    <div id="chextbt" style="display:none;" >
                                <form action="exportcsvad.php" method="get" >
                                <input type="hidden" name="fromdate_a" value="<?=  isset($_REQUEST['fromdate_a']) ? $_REQUEST['fromdate_a'] : ""; ?>" />
                                <input type="hidden" name="to_a" value="<?=  isset($_REQUEST['to_a']) ? $_REQUEST['to_a'] : ""; ?>" />
                                <input type="hidden" name="tripid_a" value="<?=  isset($_REQUEST['tripid_a']) ? $_REQUEST['tripid_a'] : ""; ?>" />
                                <input type="hidden" name="fromnumber_a" value="<?=  isset($_REQUEST['fromnumber_a']) ? $_REQUEST['fromnumber_a'] : ""; ?>" />
                                <input type="hidden" name="agentid_a" id="agemulids" value="<?=  isset($_REQUEST['agentid_a']) ? $_REQUEST['agentid_a'] : ""; ?>" />
                                <input type="hidden" name="tat_a" value="<?=  isset($_REQUEST['tat_a']) ? $_REQUEST['tat_a'] : ""; ?>" />
                                <input type="submit" class="btn1 btn-lightgreen" name="submit" value="Export CSV" />
                                </form>
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
							   
							   if($search_pp !='')
						         {
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
												    echo "
													<script type=\"text/javascript\">
													    document.getElementById(\"chextbt\").style.display = \"block\";
													</script>
													";
													
													  $astart='';$disbale='';$lock='';$bold='';$agid='';
			                                          $astart=' href="reports?fromnumber_a1='.$rowv['from'] .''.$link.' "';
													
													 
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
											
								 }
								 
                             ?>
                            </tbody>
                        </table>
                    </div>
                    <ul class="table_pagination" >
						<?php
						if($search_pp !='')
						{
                            $actual_link = $_SERVER['REQUEST_URI'];
                            $new_url = preg_replace('/&?page=[^&]*/', '', $actual_link);
							$statement=" `sms` WHERE  ".$search_pp."";
							$slt="DISTINCT `from`";
							echo '<div class="paginationn" >';
							  echo pagination($statement,20,$pagen,$url=$new_url.'&',$slt);
							echo '</div>';
						}
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

<script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datetimepicker({
            icons: {
                time: "custom-icon-down-arrow",
                date: "custom-icon-down-arrow",
                up: "custom-icon-down-arrow",
                down: "custom-icon-down-arrow",
                next: "custom-icon-next",
                previous: "custom-icon-back"
            },
            format: 'YYYY-MM-DD'
        });
        $('#datetimepicker2').datetimepicker({
            icons: {
                time: "custom-icon-down-arrow",
                date: "custom-icon-down-arrow",
                up: "custom-icon-down-arrow",
                down: "custom-icon-down-arrow",
                next: "custom-icon-next",
                previous: "custom-icon-back"
            },
             format: 'YYYY-MM-DD'
        });
    });
</script>

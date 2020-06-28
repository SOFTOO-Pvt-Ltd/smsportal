<?php
include_once 'header.php';
 $searchtxt="";
if(isset($_REQUEST['search'])) 
	{
	   $searchtxt="WHERE `text` LIKE  '".$_REQUEST['search']."%'";
	}

?>


<div class="modal group-modal">
    <div class="modal-dialog ">
        <div class="modal-content" style="width: 500px;">
            <div class="modal-header clearfix">
                <h1 class="modal-title">Recipients Lists</h1>
                <a class="btn btn-transparent close-modal pull-right">Close</a>
            </div>
            <div class="modal-body">
             <table class="table table-actions dataTable" id="appentabs">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Phone</th>
                               
                            </tr>
                            </thead>
                            <tbody>
                              <tr>
                              <td></td>
                              <td>Loading ......</td>
                              </tr> 
                           </tbody>
                           </table>
                
            </div>
        </div>
    </div>
</div>


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
                    <h2>Sent Messages List</h2>
                    
                    <div class="btnList">
                      <div class="form-group addNumbers">
                            <input class="form-control" id="search" type="text" placeholder="Search">
                            <a class="btn btn-transparent" onclick="searchrbr()"><i class="custom-icon-search"></i></a>
                        </div>
                    </div>
                   
                </div>
                <div class="contBody">
                    <div class="tableWrapper">
                        <table class="table table-actions dataTable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Created By</th>
                                <th>Created On</th>
                                <th>Receipeint/Group</th>
                                <th>Text</th>
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
									
									
								    $query_1 = "SELECT
									*,
									(SELECT `username` FROM `agent` WHERE `id`=`braodcast`.`agent_id`) as added_by
									,
									(SELECT `name` FROM `group` WHERE `id`=`braodcast`.`group_id`) as nameg
									FROM  `braodcast` 
									".$searchtxt."
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
											$tyep="Manually Enter Numbers";
											if($rowv['group_id']!='')
											{
												$tyep="Group-".$rowv['nameg'];
											}
											
										
											echo ' <tr>
											<td><div class="tdGroup">'.$rowv['id'].'</div></td>
											<td><div class="tdGroup">'.$rowv['added_by'].'</div></td>
											<td><div class="tdGroup">'.$rowv['datatime'].'</div></td>
											<td><div class="tdGroup groupModalTrigger"  style="text-decoration:underline;cursor:pointer;" 
											onClick="recipients('.$rowv['id'].')">'.$tyep.'</div></td>
											<td style="width: 550px;"><div class="tdGroup" title="'.$rowv['text'].'" >
											 <span class="textLimit">'.$rowv['text'].'</span></div></td>
											</tr>';	
											
										}
									}
                                    
                             ?>
                            
                            
                            </tbody>
                        </table>
                    </div>
                    <ul class="table_pagination"  >
                        <?php
                        $statement=" `braodcast` ".$searchtxt."";
                        //$sort="DESC";
                        echo '<div class="paginationn" >';
                        echo pagination($statement,20,$pagen,$url='?');
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

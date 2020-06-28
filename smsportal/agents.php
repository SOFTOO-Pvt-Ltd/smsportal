<?php
include_once 'header.php';
if(isset($_POST['upsubmit1']))
{
	if($_POST['agentid'] !='')
	{
		 $import="UPDATE `agent` SET 
		`username`='".addslashes($_POST['name1'])."',
		`password`='".sha1($_POST['password1'])."' 
		WHERE `id`='".$_POST['agentid']."' ";
		$result = mysqli_query($con,$import) ;
		$id=mysqli_insert_id($con);
		echo '
		<script type="text/javascript">
		 alert("Updated");
		</script>
		';
	}
	else
	{
	
	
	$query_1 = "SELECT
	`id`
	FROM  `agent` 
	where `username`='".$_POST['name1']."' ";
	$result = mysqli_query($con,$query_1) ;
	if (mysqli_error($con) != '')
	{
	return  "mysql_Error:-".mysqli_error($con);
	exit();
	}
	if (!mysqli_num_rows($result) > 0) 
	{
	
		 $import="INSERT into `agent` SET 
		`username`='".addslashes($_POST['name1'])."',
		`type`='".addslashes($_POST['roletype'])."',
		`password`='".sha1($_POST['password1'])."' ";
		$result = mysqli_query($con,$import) ;
		$id=mysqli_insert_id($con);
		echo '
		<script type="text/javascript">
		 alert("User has been created with following id:'.$id.'");
		</script>
		';
	}
	
	else
	{
		echo '
		<script type="text/javascript">
		 alert("This username is already taken.");
		</script>
		';
	}
	
	}
	
}

?>



<div class="modal creatagent-modal">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h1 class="modal-title">Create New Agent</h1>
                <a class="btn btn-transparent close-modal pull-right">Close</a>
            </div>
            <div class="modal-body">
                <form  class="form"  action="" method="POST"  onsubmit="return submitFormd121();" autocomplete="off"   >
                <input type="hidden" name="agentid" id="agentid" value="" />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>UserName</label>
<input class="form-control" type="text" name="name1" id="name1" placeholder="UserName" maxlength="20"  value="1"  />
                                <span class="alertMsg userAlert1" style="display: none;">User Name is required</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                               <label>Password</label>
<input class="form-control" type="password" name="password1" id="password1" placeholder="Password" maxlength="20" value="1"   />
                                <span class="alertMsg passwordAlert1" style="display: none;">Password is required</span>
                            </div>
                        </div>
                        
                         <div class="col-md-6">
                            <div class="form-group">
                               <label>Confirm Password</label>
<input class="form-control" type="password"  id="password2" placeholder="Confirm Password" maxlength="20"    />
                                <span class="alertMsg passwordAlert2" style="display: none;">Confirm Password is required</span>
                            </div>
                        </div>
                        
                     <div class="col-md-6">
                            <div class="form-group styleInline roletype">
                                <div class="radio">
                                    <input id="forid001" name="roletype"  type="radio"  value="1">
                                    <label for="forid001">Admin</label>
                                </div>
                            </div>
                            <div class="form-group styleInline roletype">
                                <div class="radio">
                                    <input id="forid002" name="roletype" checked type="radio"  value="0">
                                    <label for="forid002">Agent</label>
                                </div>
                            </div>
                       </div>   
                        
                           
                       
                    </div>
                   <!-- <a class="btn btn-lightgreen">Create Group</a>-->
                    <input class="btn btn-lightgreen" type="submit" id="upsubmit1" name="upsubmit1" value="Create Agent"   >
                </form>
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
                    <h2>All Agents List</h2>
                    <div class="btnList">
                        <a class="btn btn-transparent creatagent" onclick="createag();">Create Agent</a>
                    </div>
                </div>
                <div class="contBody">
                    <div class="tableWrapper">
                        <table class="table table-actions dataTable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>UserName</th>
                                <th>Role</th>
                                <th>Created On</th>
                                <th>Status</th>
                               
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
									*
									FROM  `agent` 
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
											if($rowv['active']=='0')
											{
											 $active="Active";
											  $activate="1";
											}
											else
											{
											    $active="Inactive";
												$activate="0";
											}
											
											
											if($rowv['type']=='1')
											$rowv['type']='Admin';
											else
											$rowv['type']='Agent';
											echo '<input type="hidden" id="agentname_'.$rowv['id'].'" value="'.$rowv['username'].'" />';
										
											echo ' <tr>
											<td><div class="tdGroup">'.$rowv['id'].'</div></td>
											<td><div class="tdGroup">'.$rowv['username'].'</div></td>
											<td><div class="tdGroup">'.$rowv['type'].'</div></td>
											<td><div class="tdGroup">'.$rowv['datatime'].'</div></td>
											<td>
											  <div class="tdGroup" >';
											    if($rowv['id'] ==1)
												echo '<span  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
												else
												 echo '<span Onclick="return ConfirmagentDelete('.$rowv['id'].','.$activate.');">'.$active.'</span>';
												if($rowv['active']=='0')
												{
												echo '
											       <span class="creatagent" Onclick="return agentedit('.$rowv['id'].');">Edit</span>';
										        }
												echo '
											  </div>
											  
											</td>
											</tr>';	
											
										}
									}
                                    
                             ?>
                            
                            
                            </tbody>
                        </table>
                    </div>
                    <ul class="table_pagination">
                        <?php
                        $statement=" `agent` WHERE `type`='0'";
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

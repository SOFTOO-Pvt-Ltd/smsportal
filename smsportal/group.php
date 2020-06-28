<?php
include_once 'header.php';


if(isset($_POST['upsubmit']))
{
		if(isset($_FILES['file']['name']) && $_FILES['file']['name'][0] != '')
			{		
			$tnew=time();
			$uploaddir = 'assets/csv/';
			///
			 ini_set("auto_detect_line_endings", true);
			 $fileName = $_FILES['file']['tmp_name'][0];
			 $file = fopen($fileName,"r") or exit("Unable to open file!");
			 $filesize = filesize($_FILES['file']['tmp_name'][0]);
			 $csvtype=fgetcsv($file, $filesize, ",");
	
			$handle = $_FILES['file']['tmp_name'][0];
			$handle = fopen($handle,"r") or exit("Unable to open file!");
			$csvcloumcount= count($csvtype);
			$data = fgetcsv($handle, $filesize, ",");
			
			if($csvcloumcount >= 2 &&  $data['0']=='Name' &&  $data['1']=='Phone' )// for chemical csv upload
			{}
			
				else
				{
					echo '
					<script type="text/javascript">
					alert("Format of CSV is incorrect");
					window.location="group.php";
					</script>
					';
					exit;
				}
			
			///
			
			
			
			$reportName   = $_FILES['file']['name'][0];
			$extension  = pathinfo($reportName, PATHINFO_EXTENSION);
			
			
			/*echo $extension;
			exit();*/
			
			$source=$tnew.'.'.$extension;
			$uploadfile = $uploaddir . $source;
			//move_uploaded_file($_FILES['file']['tmp_name'],$uploadfile);
			if (!move_uploaded_file($_FILES['file']['tmp_name'][0],$uploadfile)) 
				{
				   
					/*echo '
					<script type="text/javascript">
					alert("Invalid Parameters");
					window.location="group.php";
					</script>
					';
					exit;*/
				}
			 $i=0;
			 if($csvcloumcount >= 2  )// for device csv upload
				{
					$query_1 = "DELETE FROM `group` WHERE `name`='{$_POST['name']}' ";
					$result = mysqli_query($con,$query_1) ;
					$importf="INSERT into `group` SET 
					`name`='".addslashes($_POST['name'])."',
					`added_by`='abc'";
					$result = mysqli_query($con,$importf) ;
					$group_id=mysqli_insert_id($con);
					$bool='';
					while (($data = fgetcsv($handle, $filesize, ",")) !== FALSE) 
					{
						
						if (preg_match("/^((\92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/",$data[1]))
						{
							$query_1 = "SELECT
							`id`
							FROM  `group_members` 
							where `group_id`='".addslashes($group_id)."' AND  `phone`='".$data[1]."' ";
							$result = mysqli_query($con,$query_1) ;
							if (mysqli_error($con) != '')
							{
							return  "mysql_Error:-".mysqli_error($con);
							exit();
							}
							if (!mysqli_num_rows($result) > 0) 
							{	
							
								$import="INSERT into `group_members` SET 
								`group_id`='".addslashes($group_id)."',
								`name`='".addslashes($data[0])."',
								`phone`='".$data[1]."'  ";
								$result = mysqli_query($con,$import) ;
								$bool='1';
							}
						}
						
					}
					
					if($bool=='')
					{
						 $query_1 = "DELETE FROM `group` WHERE `name`='{$_POST['name']}' ";
						 $result = mysqli_query($con,$query_1) ;
						 
						echo '
						<script type="text/javascript">
						alert("Group not created due to invalid numbers");
						window.location="group.php";
						</script>
						';
						exit;
					}
				}
				
				
				echo '
				<script type="text/javascript">
				   window.location="group.php";
				</script>
				';

	             fclose($handle);
				 exit;
				
			} else if (isset($_POST['pname']) && isset($_POST['pphone'])) {
				$gname = $_POST['name'];
				$pnames = $_POST['pname'];
				$pphones = $_POST['pphone'];
				$agentusername = $_SESSION['agentusername'];
				$query_1 = "DELETE FROM `group` WHERE `name`='{$_POST['name']}' ";
				$result = mysqli_query($con,$query_1) ;
				$importf = "INSERT into `group` SET 
				`name`='".addslashes($_POST['name'])."',
				`added_by`= '$agentusername'";
				$result = mysqli_query($con,$importf) ;
				$group_id = mysqli_insert_id($con);
				$bool='';
				foreach ($pphones as $key => $value) {
					

					if (preg_match("/^((\92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/",$value))
					{
						$substr = substr($value,0,2);
						if ($substr !== '92') {
							$value = '92'.ltrim($value,'0');
						}
						$query_1 = "SELECT
						`id`
						FROM  `group_members` 
						where `group_id`='".addslashes($group_id)."' AND  `phone`='".$value."' ";
						$result = mysqli_query($con,$query_1) ;
						if (mysqli_error($con) != '')
						{
						return  "mysql_Error:-".mysqli_error($con);
						exit();
						}
						if (!mysqli_num_rows($result) > 0) 
						{	
						
							$import = "INSERT into `group_members` SET 
							`group_id`='".addslashes($group_id)."',
							`name`='".addslashes($pnames[$key])."',
							`phone`='".$value."'  ";
							$result = mysqli_query($con,$import) ;
							$bool='1';
						}
					}
				}
				if($bool=='')
				{
					 $query_1 = "DELETE FROM `group` WHERE `name`='{$_POST['name']}' ";
					 $result = mysqli_query($con,$query_1) ;
					 
					echo '
					<script type="text/javascript">
					alert("Group not created due to invalid numbers");
					window.location="group.php";
					</script>
					';
					exit;
				} else {
					echo '
					<script type="text/javascript">
						alert("Group successfully created.");
					   window.location="group.php";
					</script>
					';
					exit;
				}


			}
			else
			{
				echo "notset";
				exit;
			}
	
}
?>




<div class="modal group-modal">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h1 class="modal-title">Add New Group</h1>
                <a class="btn btn-transparent close-modal pull-right">Close</a>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" class="form"  action="" method="POST"  onsubmit="return submitFormd12();" >
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" type="text" name="name" id="name" placeholder="Group Name">
                                <span class="alertMsg gname" style="display: none;">Group Name is required</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="uploadCSV">
                                <label>Upload CSV </label>
                                <input class="form-control " type="file" name='file[]' id="file">
                                <a class="btn btn-transparent">Attach</a>
                                 
                            </div>
                            <div class="more_options">
	                            <a href="assets/csv/sample.csv"  download="sample.csv">Download Sample</a>
	                            <a id="add_num_manu"> | Add Manually</a>
                           </div>
                        </div>
                        <div class="man_wrapper" style="display: none;">
	                        <div class="col-md-6 name_wrapper">
	                        	<label>Name</label>
	                        	<div class="form-group">
	                                <input class="form-control" type="text" name="pname[]" id="pname" placeholder="Name">
	                                <span class="alertMsg pname" style="display: none;">Name is required</span>
	                            </div>
	                        </div>
	                        <div class="col-md-6 phone_wrapper">
	                        	<label>Phone</label>
	                        	<div class="form-group">
	                                <input class="form-control phone" type="text" name="pphone[]" id="pphone" placeholder="Phone">
	                                <span class="alertMsg pphone" style="display: none;">Phone is required</span>
	                            </div>
	                        </div>
	                        <div class="col-md-12 text-right">
	                        	<a id="add_more"> Add More </a>
	                        </div>
                        </div>
                    </div>
                   <!-- <a class="btn btn-lightgreen">Create Group</a>-->
                    <input class="btn btn-lightgreen" type="submit" id="upsubmit" name="upsubmit" value="Create Group"   >
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
                    <h2>All Groups List</h2>
                    <div class="btnList">
                        <a class="btn btn-transparent groupModalTrigger">Add New Group</a>
                    </div>
                </div>
                <div class="contBody">
                    <div class="tableWrapper">
                        <table class="table table-actions dataTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Added By</th>
                                <th>Created On</th>
                                <th>Members</th>
                                <th>Action</th>
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
									(SELECT COUNT(*) as members FROM `group_members` WHERE `group_id`=`group`.`id`) as members
									FROM  `group` 
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
										
											echo ' <tr>
											<td><div class="tdGroup">'.$rowv['name'].'</div></td>
											<td><div class="tdGroup">'.$rowv['added_by'].'</div></td>
											<td><div class="tdGroup">'.$rowv['datatime'].'</div></td>
											<td><div class="tdGroup">'.$rowv['members'].'</div></td>
											<td><div class="tdGroup" Onclick="return ConfirmDelete('.$rowv['id'].');"><span >Delete</span></div></td>
											</tr>';	
											
										}
									}
                                    
                             ?>
                            
                            
                            </tbody>
                        </table>
                    </div>
                    <ul class="table_pagination">
                        <?php
                        $statement=" `group`";
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

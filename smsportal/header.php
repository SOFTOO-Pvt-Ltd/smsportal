<?php
session_start();
date_default_timezone_set("Asia/Karachi");
include_once '../api/dbConfig.php';
include_once '../api/helpingMethods.php';
$currentFile = $_SERVER["PHP_SELF"];
$parts = Explode('/', $currentFile);
$pagename= $parts[count($parts) - 1];
if(  $pagename=='index.php')
{
if(isset($_SESSION['agentid']))  
	{
	   header("Location: sms.php");
	}
}
else
{
if(!isset($_SESSION['agentid']))  
	{
	   header("Location: index.php");
	}
}
$sid="";
$stext="";
$disabletxt='';
$enable="checked";
$disable='';
$query_1_t = "SELECT *
FROM  `replay` ";
$result_t = mysqli_query($con,$query_1_t) ;
if (mysqli_error($con) != '')
{
return  "mysql_Error:-".mysqli_error($con);
exit();
}
if (mysqli_num_rows($result_t) > 0) 
{	
	$row_t = mysqli_fetch_assoc($result_t);
	$sid= $row_t['id'];
	$stext= $row_t['text'];
	$enablenn= $row_t['enable'];
	if($enablenn=='0')
	{
		$disabletxt='';
		$enable="checked";
		$disable="";
	}
	else
	{
		 $disabletxt='disabled=""';
		 $disable="checked";
		 $enable="";
	}
}
if(isset($_POST['upsubmit2']))
{
	    $query_1 = "DELETE FROM `replay` ";
		$result = mysqli_query($con,$query_1) ;
		if(isset($_POST['list-radioe']))
		{
		  $endis=$_POST['list-radioe'];
		  if($endis==1)
		  {
				 $import="INSERT INTO `replay` SET 
				`text`='".addslashes($_POST['setreplayid'])."' ,
				`enable`='0'";
				$result = mysqli_query($con,$import) ;
				$id=mysqli_insert_id($con);
				$stext= $_POST['setreplayid'];
				$disabletxt='';
				$enable="checked";
				$disable="";
		  }
		  else
		  {
			    $import="INSERT INTO `replay` SET 
				`text`='".addslashes($stext)."' ,
				`enable`='1'";
				$result = mysqli_query($con,$import) ;
				$id=mysqli_insert_id($con);
				$stext= $stext;
				$disabletxt='disabled=""';
				$disable="checked";
				$enable="";
		  }
		}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sms Portal</title>
     <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta name="viewport" content="minimum-scale=1.0, width=device-width, maximum-scale=1, user-scalable=no" />

   <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600" rel="stylesheet">-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="assets/css/icomoon.css">
     <link rel="stylesheet" href="assets/css/style.css">
      <link rel="stylesheet" href="assets/css/my.css">
    
</head>
<body>






<div class="modal setreplay-modal">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h1 class="modal-title">Set Reply</h1>
                <a class="btn btn-transparent close-modal pull-right">Close</a>
            </div>
            <div class="modal-body">
                <form  class="form"  action="" method="POST" id="setreplyy"  onsubmit="return submitFormd122();" >
                    <div class="row">
                     <div class="form-group styleInline">
                            <div class="radio">
                                <input id="forid001e" name="list-radioe" <?=$enable?>  type="radio"  value="1">
                                <label for="forid001e">Enable</label>
                            </div>
                        </div>
                        <div class="form-group styleInline">
                            <div class="radio">
                                <input id="forid002e" name="list-radioe" <?=$disable?>  type="radio"  value="2">
                                <label for="forid002e">disable</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Set Auto Reply message,Max Length 700</label>
                                <textarea maxlength="700" id="setreplayid"  name="setreplayid" <?=$disabletxt?>   ><?=$stext ?></textarea>
                                 <span class="alertMsg setreplayid1" style="display: none;">Required.</span>
                            </div>
                        </div>
                        
                    </div>
                   <!-- <a class="btn btn-lightgreen">Create Group</a>-->
                    <input class="btn btn-lightgreen" type="submit" id="upsubmit2" name="upsubmit2" value="Save"   >
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal broadcast-modal">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <h1 class="modal-title">Broadcast Message</h1>
                <a class="btn btn-transparent close-modal pull-right bclooo">Close</a>
            </div>
            <div class="modal-body">
                <form class="form" id="broadcasts" action="" >
                    
                    
                       <div class="row">
                        <div class="form-group styleInline">
                            <div class="radio">
                                <input id="forid001" name="list-radio"  type="radio"  value="1">
                                <label for="forid001">Groups</label>
                            </div>
                        </div>
                        <div class="form-group styleInline">
                            <div class="radio">
                                <input id="forid002" name="list-radio" checked type="radio"  value="2">
                                <label for="forid002">Manually enter Numbers</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group selectWrapper" style="display: none">
                               <select class="form-control multiselect-ui" id="groupd" name="groupd" multiple="multiple">
                                    <!-- <option value="">Select</option> -->
                                   
                                    
                                    <?php
									 $query_1 = "SELECT
                                           `id`,
										   `name`, (SELECT COUNT(id) as members FROM `group_members` WHERE `group_id`=`group`.`id`) as members
                                            FROM  `group` 
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
                                                	$members = 'Member';
                                                	if ($rowv['members'] > 1) {
                                                		$members = 'Members';
                                                	}
													echo ' <option data-memcount="'.$rowv['members'].'" value="'.$rowv['id'].'">'.$rowv['name'].' ('.$rowv['members'].' '.$members.') </option>';
												}
											}
									?>
                               
                                </select>
                                
                                 <span class="alertMsg groupscc" style="display: none;">Select Group.</span>
                            </div>
                            <div class="form-group addNumbers" >
                                <input class="form-control phone" type="text" placeholder="Add Number" id="addphonenumber">
                                <a class="btn btn-transparent" onClick="addnew();"><i class="custom-icon-add"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Comma Separated Numbers</label>
                                <textarea id="commasepratedn"  name="commasepratedn" readonly  ></textarea>
                                 <span class="alertMsg texscc" style="display: none;">Either select group or eneter number.</span>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Message</label>
                                <span class="limitMsg"><span id="chcount">1</span>  Total Messages</span>
                                <textarea id="tex" name="tex" ></textarea>
                                 <span class="alertMsg texsp" style="display: none;">Required,Max Length 700.</span>
                            </div>
                        </div>
                        <div class="col-md-12"><p> Message will be delivered to <span id="total_recep"> 0 member.</div>
                    </div>
                    <!--<a class="btn btn-lightgreen">Send</a>-->
                    <?php /* <div class="col-md-12">
                    	<div class='col-sm-8'>
                    		<h4> Please select date/time if want to send later </h4>
				            <div class="form-group">
				                <div class='input-group date' id='datetimepicker1'>
				                    <input type='text' class="form-control" id = "ltr_datetime" />
				                    <span class="input-group-addon">
				                        <span class="glyphicon glyphicon-calendar"></span>
				                    </span>
				                    <span class="alertMsg ltr_datetime" style="display: none;">Select date/time to send message later. </span>
				                </div>
				            </div>
				        </div>
                    </div> */ ?>
                     <button class="btn btn-lightgreen" name="outbtn">Send Now</button>
                    <?php /* <a class="btn btn-primary" id="send_ltr">Send Later</a> */ ?>
                </form>
            </div>
            <div class="bar-btns">
                <a class="btn btn-lightGrey" (click)="hideModal()"> Cancel</a>
                <a class="btn btn-blue" (click)="deleteAdmin()"> Archive</a>
            </div>
        </div>
    </div>
</div>


<?php
		function pagination($query,$per_page,$page,$url,$slt='*'){   
			global $con; 
		    $query = "SELECT COUNT(".$slt.") as `num` FROM {$query}";
			$query1 = mysqli_query($con,$query)  or die(json_encode(mysql_error($con)));
			$row_4 = mysqli_fetch_assoc($query1);	
		    $total = $row_4['num'];
			$adjacents = "2"; 
			$prevlabel = "&lsaquo; Prev";
			$nextlabel = "Next &rsaquo;";
			$lastlabel = "Last &rsaquo;&rsaquo;";
			$page = ($page == 0 ? 1 : $page);  
			$start = ($page - 1) * $per_page;                               
			$prev = $page - 1;                          
			$next = $page + 1;
			$lastpage = ceil($total/$per_page);
			$lpm1 = $lastpage - 1; // //last page minus 1
			$pagination = "";
			if($lastpage > 1){   
			$pagination .= "<ul class='pagination'>";
			//$pagination .= "<li class='page_info'>Page {$page} of {$lastpage}</li>";
			if ($page > 1) $pagination.= "<li><a href='{$url}page={$prev}'>{$prevlabel}</a></li>";
			if ($lastpage < 7 + ($adjacents * 2)){   
			for ($counter = 1; $counter <= $lastpage; $counter++){
			if ($counter == $page)
			$pagination.= "<li><a style='background-color:#eee;' class='current'>{$counter}</a></li>";
			else
			$pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
			}
			} elseif($lastpage > 5 + ($adjacents * 2)){
			if($page < 1 + ($adjacents * 2)) {
			for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
			if ($counter == $page)
			$pagination.= "<li><a class='current'>{$counter}</a></li>";
			else
			$pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
			}
			$pagination.= "<li class='dot'>...</li>";
			$pagination.= "<li><a href='{$url}page={$lpm1}'>{$lpm1}</a></li>";
			$pagination.= "<li><a href='{$url}page={$lastpage}'>{$lastpage}</a></li>";  
			} elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
			
			$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
			$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
			$pagination.= "<li class='dot'>...</li>";
			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
			if ($counter == $page)
			$pagination.= "<li><a class='current'>{$counter}</a></li>";
			else
			$pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
			}
			$pagination.= "<li class='dot'>..</li>";
			$pagination.= "<li><a href='{$url}page={$lpm1}'>{$lpm1}</a></li>";
			$pagination.= "<li><a href='{$url}page={$lastpage}'>{$lastpage}</a></li>";      
			} else {
			$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
			$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
			$pagination.= "<li class='dot'>..</li>";
			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
			if ($counter == $page)
			$pagination.= "<li><a class='current'>{$counter}</a></li>";
			else
			$pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
			}
			}
			}
			if ($page < $counter - 1) 
			{
				$pagination.= "<li><a href='{$url}page={$next}'>{$nextlabel}</a></li>";
				$pagination.= "<li><a href='{$url}page=$lastpage'>{$lastlabel}</a></li>";
			}
			
			$pagination.= "</ul>";        
			}
			return $pagination;
		}
?>